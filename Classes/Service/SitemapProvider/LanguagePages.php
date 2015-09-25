<?php

/**
 * LanguagePages
 *
 * @category   Extension
 * @package    GoogleServices
 * @subpackage Service\SitemapProvider
 */


namespace FRUIT\GoogleServices\Service\SitemapProvider;

use FRUIT\GoogleServices\Controller\SitemapController;
use FRUIT\GoogleServices\Domain\Model\Node;
use FRUIT\GoogleServices\Service\SitemapDataService;
use TYPO3\CMS\Backend\Utility\BackendUtility;
use TYPO3\CMS\Core\Database\DatabaseConnection;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * LanguagePages
 *
 * @package    GoogleServices
 * @subpackage Service\SitemapProvider
 */
class LanguagePages extends Pages
{

    /**
     * Current language UID
     *
     * @var int
     */
    protected $currentLanguageUid;

    /**
     * Database
     *
     * @var DatabaseConnection $database
     */
    protected $database;

    /**
     * Content object
     *
     * @var \TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer $cObject
     */
    protected $cObject;

    /**
     *
     */
    public function __construct()
    {
        $this->currentLanguageUid = intval($GLOBALS['TSFE']->sys_language_uid);
        $this->database = $GLOBALS['TYPO3_DB'];
        $this->cObject = GeneralUtility::makeInstance('tslib_cObj');
    }

    /**
     * @param int               $startPage
     * @param array             $basePages
     * @param SitemapController $obj
     *
     * @return array
     */
    public function getRecords($startPage, $basePages, SitemapController $obj)
    {
        $nodes = array();
        foreach ($basePages as $uid) {
            if ($this->currentLanguageUid) {
                $fields = $this->cObject->enableFields('pages_language_overlay');
                $overlay = $this->database->exec_SELECTgetSingleRow('uid', 'pages_language_overlay',
                    ' pid=' . intval($uid) . ' AND sys_language_uid=' . $this->currentLanguageUid . $fields);
                if (!is_array($overlay)) {
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
            if (in_array($record['doktype'], array(4))) {
                continue;
            }

            // Check FE Access
            if ( $record['fe_group']!=0 ) continue; 
            $rootLineList = $GLOBALS['TSFE']->sys_page->getRootLine( $record['uid'] );
            $addToNode=true;
            foreach ($rootLineList as $rootPage) {
                if ( $rootPage['extendToSubpages']==1 && $rootPage['fe_group']!=0 ) { $addToNode=false; break; }
            }
            if ( $addToNode==false ) continue;

            // Build Node
            $node = new Node();
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

}
