<?php

/**
 * Sitemap node
 */

namespace FRUIT\GoogleServices\Domain\Model;

use FRUIT\GoogleServices\Domain\Model\Node\GeoNode;
use FRUIT\GoogleServices\Domain\Model\Node\ImageNode;
use FRUIT\GoogleServices\Domain\Model\Node\NewsNode;
use FRUIT\GoogleServices\Domain\Model\Node\VideoNode;

/**
 * Sitemap Node
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
     * @var GeoNode
     */
    protected $geo;

    /**
     * @var array
     */
    protected $images;

    /**
     * @var VideoNode
     */
    protected $video;

    /**
     * @var NewsNode
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
     * @return GeoNode
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
     * @param ImageNode $image
     */
    public function addImage(ImageNode $image)
    {
        $this->images[] = $image;
    }

    /**
     *
     * @return VideoNode
     */
    public function getVideo()
    {
        return $this->video;
    }

    /**
     *
     * @return NewsNode
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
     * @param string $changefreq One of the Node::CHANGE_FREQ_* constants.
     *
     * @throws \Exception
     */
    public function setChangefreq($changefreq)
    {
        $possibleValues = [
            self::CHANGE_FREQ_AWLAYS,
            self::CHANGE_FREQ_HOURLY,
            self::CHANGE_FREQ_DAILY,
            self::CHANGE_FREQ_WEEKLY,
            self::CHNAGE_FREQ_MONTHLY,
            self::CHANGE_FREQ_YEARLY,
            self::CHANGE_FREQ_NEVER,
        ];

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
     * @param GeoNode $geo
     */
    public function setGeo(GeoNode $geo)
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
     * @param VideoNode $video
     */
    public function setVideo(VideoNode $video)
    {
        $this->video = $video;
    }

    /**
     *
     * @param NewsNode $news
     */
    public function setNews(NewsNode $news)
    {
        $this->news = $news;
    }
}
