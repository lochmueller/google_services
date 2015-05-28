<?php

/* * *************************************************************
 *  Copyright notice
 *
 *  (c) 2011 Tim Lochmüller
 *  			
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 * ************************************************************* */

/**
 * Sitemap Provider
 *
 * @author timlochmueller
 */
class Tx_GoogleServices_Service_SitemapProvider_Sitemap implements Tx_GoogleServices_Interface_SitemapProviderInterface {

	/**
	 * Get the Records
	 *
	 * @param integer                                        $startPage
	 * @param array                                          $basePages
	 * @param Tx_GoogleServices_Controller_SitemapController $obj
	 *
	 * @return Tx_GoogleServices_Domain_Model_Node
	 */
	public function getRecords($startPage, $basePages, Tx_GoogleServices_Controller_SitemapController $obj) {
		$nodes = array();
		$database = $this->getDatabaseConnection();
		$res = $database->exec_SELECTquery('*', 'tt_content', 'CType="list" AND list_type="googleservices_pisitemap" AND hidden=0 AND deleted=0');
		while ($row = $database->sql_fetch_assoc($res)) {

			$uid = $row['pid'];
			if ($uid == $GLOBALS['TSFE']->id) {
				continue;
			}

			// Build URL
			$url = $obj->getUriBuilder()
				->setTargetPageUid($uid)
				->build();

			// can't generate a valid url
			if (!strlen($url)) {
				continue;
			}

			// Get Record
			$record = \TYPO3\CMS\Backend\Utility\BackendUtility::getRecord('pages', $uid);

			// Build Node
			$node = new Tx_GoogleServices_Domain_Model_Node();
			$node->setLoc($url);
			$node->setLastmod($this->getModifiedDate($record));
			$nodes[] = $node;
		}
		return $nodes;
	}

	/**
	 * get the modifiedDate
	 *
	 * @param array $record
	 *
	 * @return integer
	 */
	protected function getModifiedDate($record) {
		// Last mod
		$lastMod = $record['crdate'];
		if ($record['tstamp'] > $lastMod) {
			$lastMod = $record['tstamp'];
		}
		if ($record['SYS_LASTCHANGED'] > $lastMod) {
			$lastMod = $record['SYS_LASTCHANGED'];
		}
		return $lastMod;
	}

	/**
	 * Get the database connection
	 *
	 * @return \TYPO3\CMS\Core\Database\DatabaseConnection
	 */
	protected function getDatabaseConnection() {
		return $GLOBALS['TYPO3_DB'];
	}

}
