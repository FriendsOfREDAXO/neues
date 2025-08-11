<?php

namespace FriendsOfRedaxo\Neues;

use rex_config;

/** @var \rex_fragment $this */
$entry = $this->getVar('entry');
/** @var Entry $entry */

// Get the configured schema type, default to 'Article'
$schemaType = rex_config::get('neues', 'schema_type', 'Article');
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
    "image": <?= json_encode(Neues::htmlEncode($entry->getImages())) ?>,
    "datePublished": "<?= Neues::htmlEncode((new \DateTime($entry->getPublishDate()))->format(\DateTime::ATOM)) ?>"
}
</script>
