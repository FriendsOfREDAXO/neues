<?php

use FriendsOfRedaxo\Neues\Entry;

/** @var rex_fragment $this */

/** @var Entry $post */
$post = $this->getVar('post');
?>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <article class="post">

                <!-- Headline -->
                <?php if ('' !== $post->getName()) : ?>
                    <h1 class="mb-2 fw-bold"><?= htmlspecialchars($post->getName()) ?></h1>
                <?php endif ?>

                <!-- Date -->
                <?php if ('' < $post->getPublishDate()) : ?>
                    <p class="blog-post-meta">
                        <?= $post->getFormattedPublishDate() ?>

                        <!-- Author -->
                        <?php if (null !== $post->getAuthor()) : ?>
                            <?php if (null !== $post->getAuthor()->getName()) : ?>
                                von <span><?= htmlspecialchars($post->getAuthor()->getName()) ?></span>
                            <?php elseif(null !== $post->getAuthor()->getNickname()): ?>
                                von <span><?= htmlspecialchars($post->getAuthor()->getNickname()) ?></span>
                            <?php endif ?>
                        <?php endif ?>

                    </p>
                <?php endif ?>

                <!-- Post Image -->
                <?php if ('' !== $post->getImage()) : ?>
                    <?php
                    $media = rex_media::get($post->getImage());
                    ?>
                    <?php if (null !== $media) : ?>
                        <div class="ratio ratio-16x9 mb-3 mt-4">
                            <img src="<?= $media->getUrl() ?>" alt="<?= htmlspecialchars($media->getTitle()) ?>" class="h-100 object-fit-cover" width="200"/>
                        </div>
                    <?php endif ?>
                <?php endif ?>


                <!-- Post Content -->
                <?php if ('' !== $post->getDescription()) : ?>
                    <div class="mt-5">
                        <?= $post->getDescription() ?>
                    </div>
                <?php endif ?>

                <!-- Post Images/Gallery -->
                <?php $images = $post->getImages();
                      if (0 < count($images)) : ?>
                    <div class="mt-5 row g-3">
                        <?php foreach ($images as $image) : ?>
                            <?php
                            $media = rex_media::get($image);
                            $mediaUrl = rex_media_manager::getUrl('rex_media_medium', $image);
                            ?>
                            <?php if (null !== $media) : ?>
                                <div class="col-md-4">
                                    <a href="<?= $media->getUrl() ?>" class="d-inline-flex ratio ratio-16x9 h-100">
                                        <img src="<?= $mediaUrl ?>" alt="<?= htmlspecialchars($media->getTitle()) ?>" class="h-100 object-fit-cover" width="200"/>
                                    </a>
                                </div>
                            <?php endif ?>
                        <?php endforeach ?>
                    </div>
                <?php endif ?>

            </article>

        </div>
    </div>
</div>