<?php

namespace FriendsOfRedaxo\Neues;

/** @var \rex_fragment $this */
$entry = $this->getVar('entry');
/** @var Entry $entry */
?>
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "NewsArticle",
    "url": "<?= Neues::htmlEncode($entry->getUrl()) ?>",
    "publisher":{
        "@type":"Organization",
        "name":"<?= Neues::htmlEncode(\rex::getserverName()) ?>",
    },
    "headline": "<?= Neues::htmlEncode($entry->getName()) ?>",
    "mainEntityOfPage": "<?= Neues::htmlEncode($entry->getUrl()) ?>",
    "articleBody": "<?= Neues::htmlEncode($entry->getDescriptionAsPlaintext()) ?>",
    "image": <?= json_encode(Neues::htmlEncode($entry->getImages())) ?>,
    "datePublished": "<?= Neues::htmlEncode((new \DateTime($entry->getPublishDate()))->format(\DateTime::ATOM)) ?>"
}
</script>
