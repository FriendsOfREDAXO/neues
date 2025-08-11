<?php
/** @var rex_fragment $this */

/** @var rex_pager $pager */
$pager = $this->getVar('pager');

$currentPage = $pager->getCurrentPage();
$prevPage = $pager->getPrevPage();
$firsPage = $pager->getFirstPage();
$nextPage = $pager->getNextPage();
$lastPage = $pager->getLastPage();
$articleLink = rex_article::getCurrent()->getUrl();
?>

<?php
/**
 * The pagination snippet is based on the Bootstrap 5 pagination component.
 * See https://getbootstrap.com/docs/5.2/components/pagination/.
 *
 * The pagination will only be displayed if there is more than one page.
 */
?>

<?php if ($pager->getPageCount() > 1) : $page = 0 ?>
    <nav class="mt-5">
        <ul class="pagination justify-content-center">
            <!-- Previous -->
            <li class="page-item <?= $prevPage === $currentPage ? 'disabled' : '' ?>">
                <?php if (0 === $prevPage) : ?>
                    <a class="page-link d-inline-flex align-items-center h-100" href="<?= $prevPage === $currentPage ? '#' : $articleLink ?>">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                            <path fill-rule="evenodd" d="M11.354 1.646a.5.5 0 0 1 0 .708L5.707 8l5.647 5.646a.5.5 0 0 1-.708.708l-6-6a.5.5 0 0 1 0-.708l6-6a.5.5 0 0 1 .708 0z"/>
                        </svg>
                    </a>
                <?php else: ?>
                    <a class="page-link d-inline-flex align-items-center h-100" href="<?= $prevPage === $currentPage ? '#' : '?page=' . $pager->getRowsPerPage() * $prevPage ?>">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                            <path fill-rule="evenodd" d="M11.354 1.646a.5.5 0 0 1 0 .708L5.707 8l5.647 5.646a.5.5 0 0 1-.708.708l-6-6a.5.5 0 0 1 0-.708l6-6a.5.5 0 0 1 .708 0z"/>
                        </svg>
                    </a>
                <?php endif ?>
            </li>

            <!-- Pages -->
            <?php for ($i = 0; $i < $pager->getPageCount(); ++$i): ?>
                <li class="page-item">
                    <?php if (0 === $page) : ?>
                        <a class="page-link <?= $currentPage === $i ? 'active' : '' ?>"
                           href="<?= $articleLink ?>">
                            <?= $page + 1 ?>
                        </a>
                    <?php else: ?>
                        <a class="page-link <?= $currentPage === $i ? 'active' : '' ?>"
                           href="?page=<?= $pager->getRowsPerPage() * $page ?>">
                            <?= $page + 1 ?>
                        </a>
                    <?php endif ?>
                </li>
            <?php $page = $i + 1; endfor ?>

            <!-- Next -->
            <li class="page-item <?= $nextPage === $currentPage ? 'disabled' : '' ?>">
                <a class="page-link d-inline-flex align-items-center h-100"
                   href="<?= $nextPage === $currentPage ? '#' : '?page=' . $pager->getRowsPerPage() * $nextPage ?>">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                        <path fill-rule="evenodd" d="M4.646 1.646a.5.5 0 0 1 .708 0l6 6a.5.5 0 0 1 0 .708l-6 6a.5.5 0 0 1-.708-.708L10.293 8 4.646 2.354a.5.5 0 0 1 0-.708z"/>
                    </svg>
                </a>
            </li>
        </ul>
    </nav>
<?php endif ?>