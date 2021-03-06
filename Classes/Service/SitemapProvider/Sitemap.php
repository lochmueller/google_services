<?php

/**
 * Sitemap Provider
 */

namespace FRUIT\GoogleServices\Service\SitemapProvider;

use FRUIT\GoogleServices\Controller\SitemapController;
use FRUIT\GoogleServices\Domain\Model\Node;
use FRUIT\GoogleServices\Service\SitemapProviderInterface;
use TYPO3\CMS\Backend\Utility\BackendUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Frontend\Page\PageRepository;

/**
 * Sitemap Provider
 *
 * @author timlochmueller
 */
class Sitemap implements SitemapProviderInterface
{

    /**
     * Get the Records
     *
     * @param integer $startPage
     * @param array $basePages
     * @param SitemapController $obj
     *
     * @return array
     */
    public function getRecords($startPage, $basePages, SitemapController $obj): array
    {
        $nodes = [];
        $database = $this->getDatabaseConnection();
        /** @var PageRepository $pageRepository */
        $pageRepository = GeneralUtility::makeInstance(PageRepository::class);
        $rows = $database->exec_SELECTgetRows('*', 'tt_content', 'CType=' . $database->fullQuoteStr(
            'list',
            'tt_content'
        ) . ' AND list_type=' . $database->fullQuoteStr(
            'googleservices_pisitemap',
            'tt_content'
        ) . $pageRepository->enableFields('tt_content'));

        foreach ($rows as $row) {
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
            $record = BackendUtility::getRecord('pages', $uid);

            // Check FE Access
            if ($record['fe_group'] != 0 || $record['no_search'] != 0) {
                continue;
            }
            $rootLineList = $GLOBALS['TSFE']->sys_page->getRootLine($record['uid']);
            $addToNode = true;
            foreach ($rootLineList as $rootPage) {
                if ($rootPage['extendToSubpages'] == 1 && ($rootPage['fe_group'] != 0 || $record['no_search'] != 0)) {
                    $addToNode = false;
                    break;
                }
            }
            if ($addToNode === false) {
                continue;
            }

            // Build Node
            $node = new Node();
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
    protected function getModifiedDate($record)
    {
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
    protected function getDatabaseConnection()
    {
        return $GLOBALS['TYPO3_DB'];
    }
}
