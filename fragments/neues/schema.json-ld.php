<?php

namespace FriendsOfRedaxo\Neues;

use rex_config;
use rex_media;

/** @var \rex_fragment $this */
$entry = $this->getVar('entry');
/** @var Entry $entry */

// Get the configured schema type, default to 'Article'
$schemaType = rex_config::get('neues', 'schema_type', 'Article');

// Convert image filenames to full URLs
$imageUrls = [];
foreach ($entry->getImages() as $imageName) {
    $media = rex_media::get($imageName);
    if (null !== $media) {
        $imageUrls[] = $media->getUrl();
    }
}
?>
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "<?= Neues::htmlEncode($schemaType) ?>",
    "url": "<?= Neues::htmlEncode($entry->getUrl()) ?>",
    "publisher":{
        "@type":"Organization",
        "name":"<?= Neues::htmlEncode(\rex::getserverName()) ?>",
    },
    "headline": "<?= Neues::htmlEncode($entry->getName()) ?>",
    "mainEntityOfPage": "<?= Neues::htmlEncode($entry->getUrl()) ?>",
    "articleBody": "<?= Neues::htmlEncode($entry->getDescriptionAsPlaintext()) ?>",
    "image": <?= json_encode($imageUrls) ?>,
    "datePublished": "<?= Neues::htmlEncode((new \DateTime($entry->getPublishDate()))->format(\DateTime::ATOM)) ?>"
}
</script>
