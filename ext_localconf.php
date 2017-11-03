<?php

if (!defined('TYPO3_MODE')) {
    die('Access denied.');
}

/**
 * Sitemap
 */
\FRUIT\GoogleServices\Service\SitemapProvider::addProvider(\FRUIT\GoogleServices\Service\SitemapProvider\Pages::class);
\FRUIT\GoogleServices\Service\SitemapProvider::addProvider(\FRUIT\GoogleServices\Service\SitemapProvider\News::class);
\FRUIT\GoogleServices\Service\SitemapProvider::addProvider(\FRUIT\GoogleServices\Service\SitemapProvider\Sitemap::class);
\FRUIT\GoogleServices\Service\SitemapProvider::addProvider(\FRUIT\GoogleServices\Service\SitemapProvider\LanguagePages::class);
\FRUIT\GoogleServices\Service\SitemapProvider::addProvider(\FRUIT\GoogleServices\Service\SitemapProvider\FalImages::class);
\FRUIT\GoogleServices\Service\SitemapProvider::addProvider(\FRUIT\GoogleServices\Service\SitemapProvider\TxNews::class);

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin('FRUIT.google_services', 'piSitemap', [
    'Sitemap' => 'index',
]);

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin('FRUIT.google_services', 'piDocument', [
    'Document' => 'index',
]);

$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['cms/layout/class.tx_cms_layout.php']['list_type_Info']['googleservices_pisitemap'][] = \FRUIT\GoogleServices\Hooks\CmsLayout::class . '->renderSitemapPlugin';

$GLOBALS['TYPO3_CONF_VARS']['FE']['addRootLineFields'] .= ($GLOBALS['TYPO3_CONF_VARS']['FE']['addRootLineFields'] == '' ? '' : ',') . 'no_search';
