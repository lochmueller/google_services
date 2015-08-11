<?php

$tempColumns = array(
	'node_priority' => array(
		'exclude' => 1,
		'label'   => 'Node Priority',
		'config'  => array(
			'type' => 'input',
			'size' => '7',
			'eval' => 'double2',
		),
	),
);


\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTCAcolumns('pages', $tempColumns);
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes('pages', 'node_priority');