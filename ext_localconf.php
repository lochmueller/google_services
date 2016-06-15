<?php

if (!defined('TYPO3_MODE')) {
    die('Access denied.');
}

/**
 * Sitemap
 */
\FRUIT\GoogleServices\Service\SitemapProvider::addProvider('FRUIT\\GoogleServices\\Service\\SitemapProvider\\Pages');
\FRUIT\GoogleServices\Service\SitemapProvider::addProvider('FRUIT\\GoogleServices\\Service\\SitemapProvider\\News');
\FRUIT\GoogleServices\Service\SitemapProvider::addProvider('FRUIT\\GoogleServices\\Service\\SitemapProvider\\Sitemap');
\FRUIT\GoogleServices\Service\SitemapProvider::addProvider('FRUIT\\GoogleServices\\Service\\SitemapProvider\\LanguagePages');
\FRUIT\GoogleServices\Service\SitemapProvider::addProvider('FRUIT\\GoogleServices\\Service\\SitemapProvider\\FalImages');

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin('FRUIT.google_services', 'piSitemap', array(
    'Sitemap' => 'index',
));

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin('FRUIT.google_services', 'piDocument', array(
    'Document' => 'index',
));

$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['cms/layout/class.tx_cms_layout.php']['list_type_Info']['googleservices_pisitemap'][] = 'FRUIT\\GoogleServices\\Hooks\\CmsLayout->renderSitemapPlugin';
