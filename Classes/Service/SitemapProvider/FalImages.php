<?php
/**
 * SitemapProvider Images
 *
 * @author     Ercüment Topal <ercuement.topal@hdnet.de>
 */

use TYPO3\CMS\Backend\Utility\BackendUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Class Images
 *
 * @author     Ercüment Topal <ercuement.topal@hdnet.de>
 */
class Tx_GoogleServices_Service_SitemapProvider_FalImages extends Tx_GoogleServices_Service_SitemapProvider_Pages {

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
	 * @param int                                            $startPage
	 * @param array                                          $basePages
	 * @param Tx_GoogleServices_Controller_SitemapController $obj
	 *
	 * @return array|Tx_GoogleServices_Domain_Model_Node
	 */
	public function getRecords($startPage, $basePages, Tx_GoogleServices_Controller_SitemapController $obj) {
		$nodes = array();
		foreach ($basePages as $uid) {
			$images = $this->getImagesByPages(array($uid));
			if (!sizeof($images)) {
				continue;
			}
			$imageNodes = array();
			foreach ($images as $imageReference) {
				/** @var $imageReference \TYPO3\CMS\Core\Resource\FileReference */
				$url = GeneralUtility::getIndpEnv('TYPO3_REQUEST_HOST') . '/' . $imageReference
						->getOriginalFile()
						->getPublicUrl();

				// Build Node
				$nodeImage = new Tx_GoogleServices_Domain_Model_Node_Image();
				$nodeImage->setLoc($url);
				$nodeImage->setTitle($imageReference->getTitle());
				$nodeImage->setCaption($imageReference->getDescription());
				$imageNodes[] = $nodeImage;
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
			$record = BackendUtility::getRecord('pages', $uid);

			// exclude Doctypes
			if (in_array($record['doktype'], array(4))) {
				continue;
			}

			// Build Node
			$node = new Tx_GoogleServices_Domain_Model_Node();
			$node->setLoc($url);
			$node->setPriority($this->getPriority($startPage, $record));
			$node->setChangefreq(\Tx_GoogleServices_Service_SitemapDataService::mapTimeout2Period($record['cache_timeout']));
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
	protected function getImagesByPages(array $pages) {
		$images = array();

		if (!sizeof($pages)) {
			return $images;
		}

		$enabledFields = $this->pageRepository->enableFields('sys_file_reference');
		$enabledFields .= $this->pageRepository->enableFields('tt_content');
		$enabledFields .= $this->pageRepository->enableFields('pages');

		$rows = $this->getDatabaseConnection()->exec_SELECTgetRows('sys_file_reference.*', 'sys_file_reference, tt_content, pages', 'sys_file_reference.tablenames = \'tt_content\' AND sys_file_reference.fieldname =\'image\' AND sys_file_reference.uid_foreign = tt_content.uid AND tt_content.pid = pages.uid AND pages.uid IN (' . implode(',', $pages) . ') ' . $enabledFields);

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
	protected function getDatabaseConnection() {
		return $GLOBALS['TYPO3_DB'];
	}

}