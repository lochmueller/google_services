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
 * Sitemap Controller
 *
 * @author     Tim Lochmüller
 */
class Tx_GoogleServices_Controller_SitemapController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController {

	/**
	 * Overview About Sitemaps
	 */
	public function indexAction() {
		$pages = $this->getBasePages();
		$providers = \TYPO3\CMS\Core\Utility\GeneralUtility::trimExplode(',', $this->settings['provider'], TRUE);
		$nodes = array();

		foreach ($providers as $provider) {
			$provider = Tx_GoogleServices_Service_SitemapProvider::getProvider($provider);
			$providerNodes = $provider->getRecords(intval($this->settings['startpoint']), $pages, $this);
			$nodes = array_merge($nodes, $providerNodes);
		}
		$this->prepareAndAssignNodes($nodes);
	}

	/**
	 * Overview about Sitemaps
	 */
	public function overviewAction() {
		$pages = $this->getBasePages();
		$provider = Tx_GoogleServices_Service_SitemapProvider::getProvider('Tx_GoogleServices_Service_SitemapProvider_Sitemap');
		$nodes = $provider->getRecords(intval($this->settings['startpoint']), $pages, $this);

		$this->prepareAndAssignNodes($nodes);
	}

	/**
	 * Return a aboslute uri Builder (for Providers)
	 *
	 * @return Tx_Extbase_MVC_Web_Routing_UriBuilder
	 */
	public function getUriBuilder() {
		return $this->uriBuilder->reset()
			->setCreateAbsoluteUri(TRUE);
	}

	/**
	 * Get the base pages
	 *
	 * @return array
	 */
	protected function getBasePages() {
		$startPage = intval($this->settings['startpoint']);
		$depth = intval($this->settings['depth']);
		$pages = $this->configurationManager->getContentObject()
			->getTreeList($startPage, $depth, 0, TRUE);
		return \TYPO3\CMS\Core\Utility\GeneralUtility::trimExplode(',', $startPage . ',' . $pages, TRUE);
	}

	/**
	 * Prepare the Nodes for the Sitemap
	 *
	 * @param array $nodes
	 */
	protected function prepareAndAssignNodes($nodes) {
		if (!is_array($nodes)) {
			$nodes = array($nodes);
		}

		$nodes = $this->removeDoublicates($nodes);
		$this->view->assign('nodes', $nodes);
	}

	/**
	 *
	 * @param array $nodes
	 *
	 * @return array
	 */
	protected function removeDoublicates(array $nodes) {
		$double = array();
		foreach ($nodes as $key => $value) {
			if ($value instanceof Tx_GoogleServices_Domain_Model_Node) {
				if (in_array($value->getLoc(), $double)) {
					unset($nodes[$key]);
					continue;
				}
				$double[] = $value->getLoc();
			} else {
				unset($nodes[$key]);
			}
		}
		return $nodes;
	}

}
