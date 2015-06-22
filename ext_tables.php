<?php

if (!defined('TYPO3_MODE')) {
	die('Access denied.');
}

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile($_EXTKEY, 'Configuration/TypoScript', 'Google Services');

/**
 * Sitemap
 */
\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin($_EXTKEY, 'piSitemap', 'Google Services: Sitemap');
$GLOBALS['TCA']['tt_content']['types']['list']['subtypes_addlist']['googleservices_pisitemap'] = 'pi_flexform';
require_once(\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath('google_services', 'Classes/User/Provider.php'));
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue('googleservices_pisitemap', 'FILE:EXT:' . $_EXTKEY . '/Configuration/FlexForms/piSitemap.xml');

/**
 * Document
 */
\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin($_EXTKEY, 'piDocument', 'Google Services: Document View');
$GLOBALS['TCA']['tt_content']['types']['list']['subtypes_addlist']['googleservices_pidocument'] = 'pi_flexform';
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue('googleservices_pidocument', 'FILE:EXT:' . $_EXTKEY . '/Configuration/FlexForms/piDocument.xml');