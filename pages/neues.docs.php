<?php

/**
 * @var rex_addon $this
 * @psalm-scope-this rex_addon
 */

$mdFiles = [];
foreach (glob(rex_addon::get('neues')->getPath('docs').'/*.md') ?: [] as $file) {
    $mdFiles[mb_substr(basename($file), 0, -3)] = $file;
}

$currenMDFile = rex_request('mdfile', 'string', '01_a_intro');
if (!array_key_exists($currenMDFile, $mdFiles)) {
    $currenMDFile = '01_a_intro';
}

$page = rex_be_controller::getPageObject('neues/docs');

if (null !== $page) {
    foreach ($mdFiles as $key => $mdFile) {
        $keyWithoudPrio = mb_substr($key, 3);
        $currenMDFileWithoudPrio = mb_substr($currenMDFile, 3);
        $page->addSubpage(
            (new rex_be_page($key, rex_i18n::msg('neues_docs_'.$keyWithoudPrio)))
            ->setSubPath($mdFile)
            ->setHref('index.php?page=neues/docs&mdfile='.$key)
            ->setIsActive($key == $currenMDFile)
        );
    }
}

echo rex_view::title($this->i18n('neues_title'));

[$Toc, $Content] = rex_markdown::factory()->parseWithToc(rex_file::require($mdFiles[$currenMDFile]), 2, 3, [
    rex_markdown::SOFT_LINE_BREAKS => false,
    rex_markdown::HIGHLIGHT_PHP => true,
]);

$fragment = new rex_fragment();
$fragment->setVar('content', $Content, false);
$fragment->setVar('toc', $Toc, false);
$content = $fragment->parse('core/page/docs.php');

$fragment = new rex_fragment();
$fragment->setVar('title', rex_i18n::msg('package_help') . ' ', false);
$fragment->setVar('body', $content, false);
echo $fragment->parse('core/page/section.php');
