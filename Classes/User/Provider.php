<?php

/**
 * Class user_Tx_GoogleServices_User_Provider
 */
class user_Tx_GoogleServices_User_Provider {

	/**
	 * @param $params
	 * @param $ref
	 */
	public function select(&$params, &$ref) {
		// Base
		$providers = Tx_GoogleServices_Service_SitemapProvider::getProviders();
		$params['items'] = array();

		foreach ($providers as $id => $provider) {
			$params['items'][] = array(
				$id,
				$id
			);
		}
	}

}