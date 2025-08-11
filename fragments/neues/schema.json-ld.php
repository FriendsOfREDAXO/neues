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

// Build schema.org data as PHP array
$schemaData = [
    '@context' => 'https://schema.org',
    '@type' => $schemaType,
    'url' => $entry->getUrl(),
    'publisher' => [
        '@type' => 'Organization',
        'name' => \rex::getserverName(),
    ],
    'headline' => $entry->getName(),
    'mainEntityOfPage' => $entry->getUrl(),
    'articleBody' => $entry->getDescriptionAsPlaintext(),
    'image' => $imageUrls,
    'datePublished' => (new \DateTime($entry->getPublishDate()))->format(\DateTime::ATOM),
];
?>
<script type="application/ld+json">
<?= json_encode($schemaData, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) ?>
</script>
