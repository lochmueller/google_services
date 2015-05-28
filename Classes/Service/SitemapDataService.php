<?php

/**
 * Class Tx_GoogleServices_Service_SitemapDataService
 */
class Tx_GoogleServices_Service_SitemapDataService {

	/**
	 *
	 * @param integer $sec
	 *
	 * @return string
	 */
	public static function mapTimeout2Period($sec) {
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