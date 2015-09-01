<?php

namespace FRUIT\GoogleServices\Service;

use FRUIT\GoogleServices\Controller\SitemapController;

/**
 * Description of SitemapProivderInterface
 *
 * @author timlochmueller
 */
interface SitemapProviderInterface
{

    /**
     * Get the Records
     *
     * @param integer           $startPage
     * @param array             $basePages
     * @param SitemapController $obj
     *
     * @return array of Node objects
     */
    public function getRecords($startPage, $basePages, SitemapController $obj);
}