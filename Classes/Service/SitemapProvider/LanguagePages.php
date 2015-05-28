<?php

/**
 * LanguagePages
 *
 * @category   Extension
 * @package    GoogleServices
 * @subpackage Service\SitemapProvider
 */


/**
 * LanguagePages
 *
 * @package    GoogleServices
 * @subpackage Service\SitemapProvider
 */
class Tx_GoogleServices_Service_SitemapProvider_LanguagePages extends Tx_GoogleServices_Service_SitemapProvider_Pages {

	/**
	 * Current language UID
	 *
	 * @var int
	 */
	protected $currentLanguageUid;

	/**
	 * Database
	 *
	 * @var t3lib_db $database
	 */
	protected $database;

	/**
	 * Content object
	 *
	 * @var tslib_cObj $cObject
	 */
	protected $cObject;

	/**
	 *
	 */
	public function __construct() {
		$this->currentLanguageUid = intval($GLOBALS['TSFE']->sys_language_uid);
		$this->database = $GLOBALS['TYPO3_DB'];
		$this->cObject = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('tslib_cObj');
	}

	/**
	 * @param int                                            $startPage
	 * @param array                                          $basePages
	 * @param Tx_GoogleServices_Controller_SitemapController $obj
	 *
	 * @return array|Tx_GoogleServices_Domain_Model_Node
	 */
	public function getRecords($startPage, $basePages, Tx_GoogleServices_Controller_SitemapController $obj) {
		$nodes = array();
		foreach ($basePages as $uid) {
			if ($this->currentLanguageUid) {
				$fields = $this->cObject->enableFields('pages_language_overlay');
				$overlay = $this->database->exec_SELECTgetSingleRow('uid', 'pages_language_overlay', ' pid = ' . intval($uid) . ' AND sys_language_uid = ' . $this->currentLanguageUid . $fields);
				if (!is_array($overlay)) {
					continue;
				}
			}

			// Build URL
			$url = $obj
				->getUriBuilder()
				->setTargetPageUid($uid)
				->build();

			// can't generate a valid url
			if (!strlen($url)) {
				continue;
			}

			// Get Record
			$record = \TYPO3\CMS\Backend\Utility\BackendUtility::getRecord('pages', $uid);

			// exclude Doctypes
			if (in_array($record['doktype'], array(4))) {
				continue;
			}

			// Build Node
			$node = new Tx_GoogleServices_Domain_Model_Node();
			$node->setLoc($url);
			$node->setPriority($this->getPriority($startPage, $record));
			$node->setChangefreq(Tx_GoogleServices_Service_SitemapDataService::mapTimeout2Period($record['cache_timeout']));
			$node->setLastmod($this->getModifiedDate($record));

			#$geo = new Tx_GoogleServices_Domain_Model_Node_Geo();
			#$geo->setFormat('kml');
			#$node->setGeo($geo);

			$nodes[] = $node;
		}

		return $nodes;
	}

}
