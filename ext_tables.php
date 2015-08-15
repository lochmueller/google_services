<?php

if (!defined('TYPO3_MODE')) {
    die('Access denied.');
}

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile('google_services', 'Configuration/TypoScript',
    'Google Services');

/**
 * Sitemap
 */
\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin('google_services', 'piSitemap', 'Google Services: Sitemap');
$GLOBALS['TCA']['tt_content']['types']['list']['subtypes_addlist']['googleservices_pisitemap'] = 'pi_flexform';
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue('googleservices_pisitemap',
    'FILE:EXT:google_services/Configuration/FlexForms/piSitemap.xml');

/**
 * Document
 */
\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin('google_services', 'piDocument', 'Google Services: Document View');
$GLOBALS['TCA']['tt_content']['types']['list']['subtypes_addlist']['googleservices_pidocument'] = 'pi_flexform';
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue('googleservices_pidocument',
    'FILE:EXT:google_services/Configuration/FlexForms/piDocument.xml');