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
 * Sitemap Image
 *
 * @copyright Copyright belongs to the respective authors
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class Tx_GoogleServices_Domain_Model_Node_Image extends Tx_GoogleServices_Domain_Model_AbstractModel {

	/**
	 * Location
	 * 
	 * @var string
	 */
	protected $loc;
	
	/**
	 * Caption
	 *
	 * @var string
	 */
	protected $caption;
	
	/**
	 * Title
	 *
	 * @var string
	 */
	protected $title;
	
	/**
	 * License
	 *
	 * @var string
	 */
	protected $license;

	/**
	 *
	 * @return string 
	 */
	public function getLoc() {
		return $this->loc;
	}
	
	/**
	 * @return string
	 */
	public function getCaption(){
		return $this->caption;
	}
	
	/**
	 * @return string
	 */
	public function getTitle(){
		return $this->title;
	}
	
	/**
	 * @return string
	 */
	public function getLicense(){
		return $this->license;
	}

	/**
	 *
	 * @param string $loc
	 *
	 * @throws Exception
	 */
	public function setLoc($loc) {
		if (!filter_var($loc, FILTER_VALIDATE_URL))
			throw new Exception('The location of a google sitemap has have to be a valid URL');
		$this->loc = $loc;
	}
	
	/**
	 * @param string $caption
	 */
	public function setCaption($caption){
		$this->caption = $caption;
	}
	
	/**
	 * @param string $title
	 */
	public function setTitle($title){
		$this->title = $title;
	}
	
	/**
	 * @param string $license
	 */
	public function setLicense($license){
		$this->license = $license;
	}
	
}