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

namespace FRUIT\GoogleServices\Domain\Model;

use FRUIT\GoogleServices\Domain\Model\Node\Geo;
use FRUIT\GoogleServices\Domain\Model\Node\Image;
use FRUIT\GoogleServices\Domain\Model\Node\News;
use FRUIT\GoogleServices\Domain\Model\Node\Video;

/**
 * Sitemap Node
 *
 * @copyright Copyright belongs to the respective authors
 * @license   http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class Node extends AbstractModel
{

    /**
     * Constants for usage in setChangefreq()
     */
    const CHANGE_FREQ_AWLAYS = 'always';
    const CHANGE_FREQ_HOURLY = 'hourly';
    const CHANGE_FREQ_DAILY = 'daily';
    const CHANGE_FREQ_WEEKLY = 'weekly';
    const CHNAGE_FREQ_MONTHLY = 'monthly';
    const CHANGE_FREQ_YEARLY = 'yearly';
    const CHANGE_FREQ_NEVER = 'never';

    /**
     * Location
     *
     * @var string
     */
    protected $loc;

    /**
     * Last modifcation
     *
     * @var string
     */
    protected $lastmod;

    /**
     * Change frequency
     *
     * @var string
     */
    protected $changefreq;

    /**
     * Priority
     *
     * @var float
     */
    protected $priority;

    /**
     * @var Geo
     */
    protected $geo;

    /**
     * @var array
     */
    protected $images;

    /**
     * @var Video
     */
    protected $video;

    /**
     * @var News
     */
    protected $news;

    /**
     *
     * @return string
     */
    public function getLoc()
    {
        return $this->loc;
    }

    /**
     *
     * @return string
     */
    public function getLastmod()
    {
        return $this->lastmod;
    }

    /**
     *
     * @return string
     */
    public function getChangefreq()
    {
        if (!strlen($this->changefreq)) {
            return false;
        }
        return $this->changefreq;
    }

    /**
     *
     * @return float
     */
    public function getPriority()
    {
        if ($this->priority === null) {
            return -1;
        }
        return $this->priority;
    }

    /**
     * @return Geo
     */
    public function getGeo()
    {
        return $this->geo;
    }

    /**
     *
     * @return array
     */
    public function getImages()
    {
        return $this->images;
    }

    /**
     * @param Image $image
     */
    public function addImage(Image $image)
    {
        $this->images[] = $image;
    }

    /**
     *
     * @return Video
     */
    public function getVideo()
    {
        return $this->video;
    }

    /**
     *
     * @return News
     */
    public function getNews()
    {
        return $this->news;
    }

    /**
     *
     * @param string $loc
     *
     * @throws \Exception
     */
    public function setLoc($loc)
    {
        if (!filter_var($loc, FILTER_VALIDATE_URL)) {
            throw new \Exception('The location of a google sitemap has have to be a valid URL');
        }
        $this->loc = $loc;
    }

    /**
     *
     * @param string $lastmod
     */
    public function setLastmod($lastmod)
    {

        // timestamp or parsable date

        $this->lastmod = $lastmod;
    }

    /**
     * @todo Implement integer converter
     *
     * @param string $changefreq One of the Tx_GoogleServices_Domain_Model_Node::CHANGE_FREQ_* constants.
     *
     * @throws \Exception
     */
    public function setChangefreq($changefreq)
    {
        $possibleValues = array(
            self::CHANGE_FREQ_AWLAYS,
            self::CHANGE_FREQ_HOURLY,
            self::CHANGE_FREQ_DAILY,
            self::CHANGE_FREQ_WEEKLY,
            self::CHNAGE_FREQ_MONTHLY,
            self::CHANGE_FREQ_YEARLY,
            self::CHANGE_FREQ_NEVER,
        );

        if (!in_array(trim($changefreq), $possibleValues)) {
            throw new \Exception('The value of the changefreq have to be one of theses values: ' . implode(',', $possibleValues));
        }
        $this->changefreq = $changefreq;
    }

    /**
     *
     * @param float $priority
     *
     * @throws \Exception
     */
    public function setPriority($priority)
    {
        if (!is_float($priority)) {
            throw new \Exception('Parameter $priority has to be a float value');
        }
        if ($priority < 0) {
            $this->setPriority(0.0);
        }
        if ($priority > 1) {
            $this->setPriority(1.0);
        }
        $this->priority = $priority;
    }

    /**
     * @param Geo $geo
     */
    public function setGeo(Geo $geo)
    {
        $this->geo = $geo;
    }

    /**
     *
     * @param array $images
     */
    public function setImages(array $images)
    {
        $this->images = $images;
    }

    /**
     *
     * @param Video $video
     */
    public function setVideo(Video $video)
    {
        $this->video = $video;
    }

    /**
     *
     * @param News $news
     */
    public function setNews(News $news)
    {
        $this->news = $news;
    }

}