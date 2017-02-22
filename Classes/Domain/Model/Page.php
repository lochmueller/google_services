<?php

/**
 * Page
 */

namespace FRUIT\GoogleServices\Domain\Model;

/**
 * Page
 */
class Page extends AbstractModel
{

    /**
     * Title
     *
     * @var string
     * @validate NotEmpty
     */
    protected $title;

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set title
     *
     * @param string $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }
}
