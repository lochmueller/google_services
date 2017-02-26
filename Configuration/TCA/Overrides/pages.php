<?php

$tempColumns = [
    'node_priority' => [
        'exclude' => 1,
        'label' => 'Node Priority',
        'config' => [
            'type' => 'input',
            'size' => '7',
            'eval' => 'double2',
        ],
    ],
    'exclude_sitemap' => [
        'exclude' => 1,
        'label' => 'Exclude from Sitemap',
        'config' => [
            'type' => 'check',
        ],
    ],
];


\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTCAcolumns('pages', $tempColumns);
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes('pages', 'node_priority');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes('pages', 'exclude_sitemap');
