<?php

use Ramsey\uuid\uuid;

class neues_entry extends \rex_yform_manager_dataset
{
    private $startDate = null;
    private $endDate = null;
    private $location = null;
    private $category = null;
    private $offer = null;

    public static function generateuuid($id = null) :string
    {
        return uuid::uuid3(uuid::NAMESPACE_URL, $id);
    }

    public function getCategory()
    {
        $this->category = $this->getRelatedDataset('event_category_id');
        return $this->category;
    }

    public function getIcs()
    {
        $UID = $this->getUid();
        
        $vCalendar = new \Eluceo\iCal\Component\Calendar('-//' . date("Y") . '//#' . rex::getServerName() . '//' . strtoupper((rex_clang::getCurrent())->getCode()));
        date_default_timezone_set(rex::getProperty('timezone'));
        
        $vEvent = new \Eluceo\iCal\Component\Event($UID);
        
        // date/time
        $vEvent
        ->setUseTimezone(true)
        ->setDtStart($this->getStartDate())
        ->setDtEnd($this->getEndDate())
        ->setCreated(new \DateTime($this->getValue("createdate")))
        ->setModified(new \DateTime($this->getValue("updatedate")))
        ->setNoTime($this->getValue("all_day"))
        // ->setNoTime($is_fulltime) // Wenn Ganztag
        // ->setCategories(explode(",", $sked['entry']->category_name))
        ->setSummary($this->getName())
        ->setDescription($this->getDescriptionAsPlaintext());
        
        // add location
        $locationICS = $this->getLocation();
        if (isset($locationICS)){
            $ics_lat = $locationICS->getValue('lat');
            $ics_lng = $locationICS->getValue('lng');
            $vEvent->setLocation($locationICS->getLocationAsString(), $locationICS->getValue('name'), $ics_lat != '' ? $ics_lat . ',' . $ics_lng : '');
            // fehlt: set timezone of location
        }
        
        //  add event to calendar
        $vCalendar->addComponent($vEvent);
        
        return $vCalendar->render();
        // ob_clean();
        
        // exit($vEvent);
    }

    public function getLocation()
    {
        if($this->location === null) {
               $this->location = $this->getRelatedDataset('location');
        }
        return $this->location;
    }
    
    public function getTimezone($lat, $lng){
        $event_timezone = "https://maps.googleapis.com/maps/api/timezone/json?location=" . $lat . "," . $lng . "&timestamp=" . time() . "&sensor=false";
        $event_location_time_json = file_get_contents($event_timezone);
        return $event_location_time_json;
    }

    public function getOfferAll()
    {
        // return $this->getRelatedCollection('offer'); // Fehlerhaft. Yform Issue #
    }

    public function getImage() :string
    {
        return $this->image;
    }
    public function getMedia()
    {
        return rex_media::get($this->image);
    }

    public function getDescriptionAsPlaintext() :string
    {
        return strip_tags($this->description);
    }
    public function getIcsStatus()
    {
        return strip_tags($this->eventStatus);
    }
    public function getUid()
    {
        if ($this->uid === "" && $this->getValue("uid") === "") {
            $this->uid = self::generateUuid($this->id);

            rex_sql::factory()->setQuery("UPDATE rex_neues_entry SET uid = :uid WHERE id = :id", [":uid"=>$this->uid, ":id" => $this->getId()]);
        }
        return $this->uid;
    }

    public function getJsonLd()
    {
        $fragment = new rex_fragment();
        $fragment->setVar("neues_entry", $this);
        return $fragment->parse('event-date-single.json-ld.php');
    }

    private function getDateTime($date = null, $time = "00:00")
    {
        $time = explode(":", $time);
        $dateTime = new DateTime($date);
        $dateTime->setTime($time[0], $time[1]);

        return $dateTime;
    }

    public function getStartDate()
    {
        $this->startDate = $this->getDateTime($this->getValue("startDate"), $this->getValue("startTime"));
        return $this->startDate;
    }
    public function getEndDate()
    {
        $this->endDate = $this->getDateTime($this->getValue("endDate"), $this->getValue("endTime"));
        return $this->endDate;
    }
    
    public function getName()
    {
        return $this->getValue("name");
    }
}
