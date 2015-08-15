<?php

/**
 * Sitemap controller
 *
 * @author Tim Lochmüller
 */

namespace FRUIT\GoogleServices\Controller;

use FRUIT\GoogleServices\Domain\Model\Node;
use FRUIT\GoogleServices\Service\SitemapProvider;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Mvc\Web\Routing\UriBuilder;

/**
 * Sitemap Controller
 */
class SitemapController extends AbstractController
{

    /**
     * Overview About Sitemaps
     */
    public function indexAction()
    {
        $pages = $this->getBasePages();
        $providers = GeneralUtility::trimExplode(',', $this->settings['provider'], true);
        $nodes = array();

        foreach ($providers as $provider) {
            $provider = SitemapProvider::getProvider($provider);
            $providerNodes = $provider->getRecords(intval($this->settings['startpoint']), $pages, $this);
            $nodes = array_merge($nodes, $providerNodes);
        }
        $this->prepareAndAssignNodes($nodes);
    }

    /**
     * Overview about Sitemaps
     */
    public function overviewAction()
    {
        $pages = $this->getBasePages();
        $provider = SitemapProvider::getProvider('Tx_GoogleServices_Service_SitemapProvider_Sitemap');
        $nodes = $provider->getRecords(intval($this->settings['startpoint']), $pages, $this);

        $this->prepareAndAssignNodes($nodes);
    }

    /**
     * Return a aboslute uri Builder (for Providers)
     *
     * @return UriBuilder
     */
    public function getUriBuilder()
    {
        return $this->uriBuilder->reset()
            ->setCreateAbsoluteUri(true);
    }

    /**
     * Get the base pages
     *
     * @return array
     */
    protected function getBasePages()
    {
        $startPage = intval($this->settings['startpoint']);
        $depth = intval($this->settings['depth']);
        $pages = $this->configurationManager->getContentObject()
            ->getTreeList($startPage, $depth, 0, true);
        return GeneralUtility::trimExplode(',', $startPage . ',' . $pages, true);
    }

    /**
     * Prepare the Nodes for the Sitemap
     *
     * @param array $nodes
     */
    protected function prepareAndAssignNodes($nodes)
    {
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
    protected function removeDoublicates(array $nodes)
    {
        $double = array();
        foreach ($nodes as $key => $value) {
            if ($value instanceof Node) {
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
