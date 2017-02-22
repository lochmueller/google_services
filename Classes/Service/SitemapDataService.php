<?php

/**
 * Sitemap data service
 */

namespace FRUIT\GoogleServices\Service;

/**
 * Sitemap data service
 */
class SitemapDataService
{

    /**
     * Map timestamp to period
     *
     * @param integer $sec
     *
     * @return string
     */
    public static function mapTimeout2Period($sec)
    {
        if ($sec <= 0) {
            return 'monthly';
        }
        if ($sec <= 1800) {
            return 'always';
        }
        if ($sec <= 14400) {
            return 'hourly';
        }
        if ($sec <= 172800) {
            return 'daily';
        }
        if ($sec <= 604800) {
            return 'weekly';
        }
        return 'monthly';
    }
}
