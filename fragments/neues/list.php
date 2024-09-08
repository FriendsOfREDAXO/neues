<?php

/** @var rex_fragment $this */

use FriendsOfRedaxo\Neues\Entry;

/** @var rex_pager $pager */
$pager = $this->getVar('pager');

/** @var rex_yform_manager_collection<Entry> $posts */
$posts = $this->getVar('posts');
?>

<!-- Entry list -->
<div class="container">
    <div class="row row-cols-1 row-cols-md-2 g-3">
        <?php if(0 < count($posts)) { ?>
            <?php foreach ($posts as $post) : ?>
                <div class="col">
                    <?php
                    $fragment = new rex_fragment();
                    $fragment->setVar('post', $post);
                    echo $fragment->parse('neues/list-entry.php');
                    ?>
                </div>
            <?php endforeach ?>
        <?php } else { ?>
            <div class="placeholder">
                <?php if(null !== rex_config::get('neues', 'no_entries_placeholder')) { ?>
                    <p><?= rex_i18n::msg('no_entries_placeholder') ?></p>
                    <?php } ?>
                </div>
            <?php } ?>
    </div>
</div>

<!-- Pagination -->
<?php
$fragment = new rex_fragment();
$fragment->setVar('pager', $pager);
echo $fragment->parse('neues/pagination.php');
?>