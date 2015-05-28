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
 * Description of SitemapProvider
 *
 * @author timlochmueller
 */
class Tx_GoogleServices_Service_SitemapProvider {

	/**
	 * Provider Storage
	 *
	 * @var Array
	 */
	private static $provider = array();

	/**
	 * Add a Sitemap Provider
	 *
	 * @param string $filePath
	 * @param string $className
	 */
	public static function addProvider($filePath, $className) {
		self::$provider[$className] = $filePath;
	}

	/**
	 * Get all Providers
	 *
	 * @return array
	 */
	public static function getProviders() {
		return self::$provider;
	}

	/**
	 * Get a provider
	 *
	 * @param string $name
	 * @param string $path
	 *
	 * @throws \TYPO3\CMS\Extbase\Mvc\Exception\InvalidArgumentValueException
	 * @return object
	 */
	public static function getProvider($name, $path = NULL) {
		if ($path != NULL) {
			self::addProvider($path, $name);
			return self::getProvider($name);
		}

		if (!isset(self::$provider[$name])) {
			throw new \TYPO3\CMS\Extbase\Mvc\Exception\InvalidArgumentValueException($name . ' not exists');
		}
		require_once(self::$provider[$name]);

		$obj = new \TYPO3\CMS\Extbase\Object\ObjectManager();
		return $obj->get($name);
	}

}