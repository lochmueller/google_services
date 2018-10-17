<?php

/**
 * An abstract XML View
 */

namespace FRUIT\GoogleServices\View;

use TYPO3\CMS\Fluid\View\TemplateView;

/**
 * An abstract XML View
 *
 * @api
 */
abstract class AbstractXmlView extends TemplateView
{

    /**
     * Render XML Content as single Output
     * 
     * @param string $actionName 
     */
    public function render($actionName = null)
    {
        $c = parent::render($actionName);
        header("Content-Type:text/xml");
        die($c);
    }
}
