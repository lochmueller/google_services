<?php

if (!defined('TYPO3_MODE')) {
	die('Access denied.');
}

/**
 * Sitemap
 */
require_once(\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath('google_services', 'Classes/Service/SitemapProvider.php'));
Tx_GoogleServices_Service_SitemapProvider::addProvider(\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath('google_services', 'Classes/Service/SitemapProvider/Pages.php'), 'Tx_GoogleServices_Service_SitemapProvider_Pages');
Tx_GoogleServices_Service_SitemapProvider::addProvider(\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath('google_services', 'Classes/Service/SitemapProvider/News.php'), 'Tx_GoogleServices_Service_SitemapProvider_News');
Tx_GoogleServices_Service_SitemapProvider::addProvider(\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath('google_services', 'Classes/Service/SitemapProvider/Sitemap.php'), 'Tx_GoogleServices_Service_SitemapProvider_Sitemap');
Tx_GoogleServices_Service_SitemapProvider::addProvider(\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath('google_services', 'Classes/Service/SitemapProvider/LanguagePages.php'), 'Tx_GoogleServices_Service_SitemapProvider_LanguagePages');
Tx_GoogleServices_Service_SitemapProvider::addProvider(\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath('google_services', 'Classes/Service/SitemapProvider/FalImages.php'), 'Tx_GoogleServices_Service_SitemapProvider_FalImages');

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin($_EXTKEY, 'piSitemap', array(
	'Sitemap' => 'index',
), array(
	'Sitemap' => 'index',
));

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin($_EXTKEY, 'piDocument', array(
	'Document' => 'index',
));

$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['cms/layout/class.tx_cms_layout.php']['list_type_Info']['googleservices_pisitemap'][] = 'Tx_GoogleServices_Hooks_CmsLayout->renderSitemapPlugin';