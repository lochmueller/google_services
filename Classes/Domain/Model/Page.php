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
     * The title
     *
     * @var string
     * @validate NotEmpty
     */
    protected $title;

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }
}
