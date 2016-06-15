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

namespace FRUIT\GoogleServices\Service\SitemapProvider;

use FRUIT\GoogleServices\Controller\SitemapController;
use FRUIT\GoogleServices\Domain\Model\Node;
use FRUIT\GoogleServices\Service\SitemapProviderInterface;
use TYPO3\CMS\Backend\Utility\BackendUtility;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Core\Utility\MathUtility;

/**
 * Description of Pages
 *
 * @author timlochmueller
 */
class News implements SitemapProviderInterface
{

    /**
     * Get the Records
     *
     * @param integer           $startPage
     * @param array             $basePages
     * @param SitemapController $obj
     *
     * @throws \Exception
     * @return array
     */
    public function getRecords($startPage, $basePages, SitemapController $obj)
    {
        $nodes = array();
        if (!ExtensionManagementUtility::isLoaded('tt_news')) {
            return $nodes;
        }
        if (!MathUtility::canBeInterpretedAsInteger($GLOBALS['TSFE']->tmpl->setup['plugin.']['tt_news.']['singlePid'])) {
            throw new \Exception('You have to set tt_news singlePid.');
        }
        $singlePid = intval($GLOBALS['TSFE']->tmpl->setup['plugin.']['tt_news.']['singlePid']);
        $news = $this->getRecordsByField('tt_news', 'pid', implode(',', $basePages));
        foreach ($news as $record) {
            // Alternative Single PID
            $alternativeSinglePid = $this->alternativeSinglePid($record['uid']);
            $linkPid = ($alternativeSinglePid) ? $alternativeSinglePid : $singlePid;
            // Build URL
            $url = $obj->getUriBuilder()
                ->setArguments(array('tx_ttnews' => array('tt_news' => $record['uid'])))
                ->setTargetPageUid($linkPid)
                ->build();
            // can't generate a valid url
            if (!strlen($url)) {
                continue;
            }
            // Build Node
            $node = new Node();
            $node->setLoc($url);
            $node->setPriority($this->getPriority($record));
            $node->setChangefreq('monthly');
            $node->setLastmod($this->getModifiedDate($record));
            $nodes[] = $node;
        }
        return $nodes;
    }

    /**
     * Get the Categories single page ID
     *
     * @param $newsId
     *
     * @return bool|int
     */
    protected function alternativeSinglePid($newsId)
    {
        $database = $this->getDatabaseConnection();
        $rows = $database->exec_SELECTgetRows(
            'tt_news_cat.single_pid',
            'tt_news_cat, tt_news_cat_mm',
            'tt_news_cat_mm.uid_local=' . intval($newsId) . ' AND tt_news_cat_mm.uid_foreign=tt_news_cat.uid',
            '',
            'tt_news_cat_mm.sorting'
        );
        foreach ($rows as $row) {
            if (intval($row['single_pid']) > 0) {
                return intval($row['single_pid']);
            }
        }
        return false;
    }

    /**
     * Based on t3lib_Befunc::getRecordsByField
     *
     * @param string $theTable
     * @param string $theField
     * @param string $theValue
     * @param string $whereClause
     * @param string $groupBy
     * @param string $orderBy
     * @param string $limit
     * @param bool   $useDeleteClause
     *
     * @return array
     */
    public function getRecordsByField(
        $theTable,
        $theField,
        $theValue,
        $whereClause = '',
        $groupBy = '',
        $orderBy = '',
        $limit = '',
        $useDeleteClause = true
    ) {
        if (is_array($GLOBALS['TCA'][$theTable])) {
            $database = $this->getDatabaseConnection();
            return $database->exec_SELECTgetRows(
                '*',
                $theTable,
                $theField . ' IN (' . $theValue . ')' . ($useDeleteClause ? BackendUtility::deleteClause($theTable) . ' ' : '') . BackendUtility::versioningPlaceholderClause($theTable) . ' ' . $whereClause,
                // whereClauseMightContainGroupOrderBy
                $groupBy,
                $orderBy,
                $limit
            );
        }
        return array();
    }

    /**
     * Get the priority
     *
     * @param array $record
     *
     * @internal param int $startPage
     * @return float
     */
    protected function getPriority($record)
    {
        $prio = 0.9;
        if ($record['archivedate'] > 0 && $record['archivedate'] < time()) {
            $prio = 0.8;
        }
        return $prio;
    }

    /**
     * get the modifiedDate
     *
     * @param array $record
     *
     * @return integer
     */
    protected function getModifiedDate($record)
    {
        // Last mod
        $lastMod = $record['crdate'];
        if ($record['tstamp'] > $lastMod) {
            $lastMod = $record['tstamp'];
        }
        return $lastMod;
    }

    /**
     * Get the database connection
     *
     * @return \TYPO3\CMS\Core\Database\DatabaseConnection
     */
    protected function getDatabaseConnection()
    {
        return $GLOBALS['TYPO3_DB'];
    }
}
