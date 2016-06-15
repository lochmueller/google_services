<?php
/**
 * SitemapProvider Images
 *
 * @author     Ercüment Topal <ercuement.topal@hdnet.de>
 */

namespace FRUIT\GoogleServices\Service\SitemapProvider;

use FRUIT\GoogleServices\Controller\SitemapController;
use FRUIT\GoogleServices\Domain\Model\Node;
use FRUIT\GoogleServices\Domain\Model\Node\Image;
use FRUIT\GoogleServices\Service\SitemapDataService;
use TYPO3\CMS\Backend\Utility\BackendUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Class Images
 *
 * @author     Ercüment Topal <ercuement.topal@hdnet.de>
 */
class FalImages extends Pages
{

    /**
     * Resource factory to build objects
     *
     * @var \TYPO3\CMS\Core\Resource\ResourceFactory
     * @inject
     */
    protected $resourceFactory;

    /**
     * Page repository for manipulate the SQL queries
     *
     * @var \TYPO3\CMS\Frontend\Page\PageRepository
     * @inject
     */
    protected $pageRepository;

    /**
     * Get the records
     *
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
            $images = $this->getImagesByPages(array($uid));
            if (!sizeof($images)) {
                continue;
            }
            $imageNodes = array();
            foreach ($images as $imageReference) {
                /** @var $imageReference \TYPO3\CMS\Core\Resource\FileReference */
                $url = GeneralUtility::getIndpEnv('TYPO3_REQUEST_HOST') . '/' . $imageReference->getOriginalFile()
                        ->getPublicUrl();

                // Build Node
                $nodeImage = new Image();
                $nodeImage->setLoc($url);
                $nodeImage->setTitle($imageReference->getTitle());
                $nodeImage->setCaption($imageReference->getDescription());
                $imageNodes[] = $nodeImage;
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

            // Build Node
            $node = new Node();
            $node->setLoc($url);
            $node->setPriority($this->getPriority($startPage, $record));
            $node->setChangefreq(SitemapDataService::mapTimeout2Period($record['cache_timeout']));
            $node->setLastmod($this->getModifiedDate($record));
            $node->setImages($imageNodes);


            $nodes[] = $node;
        }

        return $nodes;
    }

    /**
     * Get alle images on the given pages
     *
     * @param array $pages
     *
     * @return array
     */
    protected function getImagesByPages(array $pages)
    {
        $images = array();

        if (!sizeof($pages)) {
            return $images;
        }

        $enabledFields = $this->pageRepository->enableFields('sys_file_reference');
        $enabledFields .= $this->pageRepository->enableFields('tt_content');
        $enabledFields .= $this->pageRepository->enableFields('pages');

        $database = $this->getDatabaseConnection();
        $rows = $database->exec_SELECTgetRows(
            'sys_file_reference.*',
            'sys_file_reference, tt_content, pages',
            'sys_file_reference.tablenames=' . $database->fullQuoteStr(
                'tt_content',
                'sys_file_reference'
            ) . ' AND sys_file_reference.fieldname=' . $database->fullQuoteStr(
                'image',
                'sys_file_reference'
            ) . ' AND sys_file_reference.uid_foreign=tt_content.uid AND tt_content.pid=pages.uid AND pages.uid IN (' . implode(
                ',',
                $pages
            ) . ') ' . $enabledFields
        );

        foreach ($rows as $row) {
            $images[] = $this->resourceFactory->getFileReferenceObject($row['uid'], $row);
        }
        return $images;
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
