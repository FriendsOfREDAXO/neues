<?php

use FriendsOfRedaxo\Neues\Entry;

/** @var rex_fragment $this */

/** @var Entry $post */
$post = $this->getVar('post');
?>

<div class="row g-0 border rounded overflow-hidden flex-md-row mb-4 shadow-sm h-md-250 position-relative h-100">
    <div class="col p-4 d-flex flex-column position-static">

        <!-- Categories -->
        <?php if ($post->getCategories()) : ?>
            <p class="d-inline-block mb-2 text-primary-emphasis">
                <?= htmlspecialchars(implode(', ', $post->getCategories()->toKeyValue('id', 'name'))) ?>
            </p>
        <?php endif ?>

        <!-- Title -->
        <?php if ('' !== $post->getName()) : ?>
            <h3 class="mb-0">
                <a href="<?= rex_getUrl('', '', ['neues-entry-id' => $post->getId()]) ?>" class="stretched-link"><?= $post->getName() ?></a>
            </h3>
        <?php endif ?>

        <!-- Date -->
        <?php if ($post->getPublishDate()) : ?>
            <div class="mb-2 text-body-secondary">
                <?= $post->getFormattedPublishDate() ?>
            </div>
        <?php endif ?>

        <!-- Teaser -->
        <?php if ('' !== $post->getTeaser()) : ?>
            <p class="card-text mb-0">
                <?= htmlspecialchars($post->getTeaser()) ?>
            </p>
        <?php endif ?>
    </div>

    <!-- Image -->
    <?php if ('' !== $post->getImage()) : ?>
    <?php
    $media = rex_media::get($post->getImage());
    $mediaUrl = rex_media_manager::getUrl('rex_media_medium', $post->getImage());
    ?>
        <?php if ($media) : ?>
            <div class="col-auto d-none d-lg-block">
                <img src="<?= $mediaUrl ?>" alt="<?= htmlspecialchars($media->getTitle()) ?>" class="h-100 object-fit-cover" width="200"/>
            </div>
        <?php endif ?>
    <?php endif ?>
</div>