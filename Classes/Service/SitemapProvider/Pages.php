<?php

/**
 * Description of Pages
 */

namespace FRUIT\GoogleServices\Service\SitemapProvider;

use FRUIT\GoogleServices\Controller\SitemapController;
use FRUIT\GoogleServices\Domain\Model\Node;
use FRUIT\GoogleServices\Service\SitemapDataService;
use FRUIT\GoogleServices\Service\SitemapProviderInterface;
use TYPO3\CMS\Backend\Utility\BackendUtility;
use TYPO3\CMS\Core\Utility\VersionNumberUtility;
use TYPO3\CMS\Frontend\Page\PageRepository;

/**
 * Description of Pages
 *
 * @author timlochmueller
 */
class Pages implements SitemapProviderInterface
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
        foreach ($basePages as $uid) {
            // If currently in another language than default, check if the page is translated - else continue
            if ($GLOBALS['TSFE']->sys_language_uid != 0) {
                $localizedPagesTable = 'pages';

                // in TYPO3 6.x the localization isn't correctly handled when using BackendUtility::getRecordLocalization('pages')
                // so we have to use the 'pages_language_overlay' table as param
                if (VersionNumberUtility::convertVersionNumberToInteger(TYPO3_version) < 7000000) {
                    $localizedPagesTable = 'pages_language_overlay';
                }

                if (BackendUtility::getRecordLocalization($localizedPagesTable, $uid, $GLOBALS['TSFE']->sys_language_uid) == false) {
                    continue;
                }
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

            // exclude Doctypes
            if (in_array($record['doktype'], [
                PageRepository::DOKTYPE_LINK,
                PageRepository::DOKTYPE_SHORTCUT,
                PageRepository::DOKTYPE_SPACER
            ])) {
                continue;
            }

            // Exclude pages with exclude flag
            if ($record['exclude_sitemap']) {
                continue;
            }

                // Check FE Access
            if ($record['fe_group'] != 0 || $record['no_search'] != 0) {
                continue;
            }

            $rootLineList = $GLOBALS['TSFE']->sys_page->getRootLine($record['uid']);
            $addToNode = true;
            foreach ($rootLineList as $rootPage) {
                if ($rootPage['extendToSubpages'] == 1 && ($rootPage['fe_group'] != 0 || $rootPage['no_search'] != 0)) {
                    $addToNode = false;
                    break;
                }
            }
            if ($addToNode === false) {
                continue;
            }

            // Build Node
            $node = new Node();
            $node->setPid($uid);
            $node->setLoc($url);
            $node->setPriority($this->getPriority($startPage, $record));
            $node->setChangefreq(SitemapDataService::mapTimeout2Period($record['cache_timeout']));
            $node->setLastmod($this->getModifiedDate($record));

            #$geo = new Geo();
            #$geo->setFormat('kml');
            #$node->setGeo($geo);

            $nodes[] = $node;
        }

        return $nodes;
    }

    /**
     * Get the priority
     *
     * @param integer $startPage
     * @param array $record
     *
     * @return float
     */
    protected function getPriority($startPage, $record)
    {
        $nodePrio = (float)$record['node_priority'];
        if ($nodePrio > 0) {
            return $nodePrio;
        }

        // Prio
        $rootline = $GLOBALS['TSFE']->sys_page->getRootLine($record['uid']);
        $find = false;
        foreach ($rootline as $key => $value) {
            if ($find) {
                unset($rootline[$key]);
            }
            if ($value['uid'] == $startPage) {
                $find = true;
            }
        }

        switch (sizeof($rootline)) {
            case 1:
                return 1.0;
            case 2:
                return 0.9;
            case 3:
                return 0.9;
            case 4:
                return 0.7;
            default:
                return 0.5;
        }
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
}
