<?php

/**
 * CmsLayout
 */

namespace FRUIT\GoogleServices\Hooks;

use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * CmsLayout
 */
class CmsLayout
{

    /**
     * render sitemap plugin information
     *
     * @param array $params
     * @param object $object
     *
     * @return string
     */
    public function renderSitemapPlugin($params, $object)
    {
        $xml = $params['row']['pi_flexform'];
        $data = GeneralUtility::xml2array($xml);

        if (!isset($data['data'])) {
            return '[no Configuration]';
        }

        if (!isset($data['data']['sDEF'])) {
            return '[no Configuration]';
        }

        if (!isset($data['data']['sDEF']['lDEF'])) {
            return '[no Configuration]';
        }

        $configurationData = $data['data']['sDEF']['lDEF'];

        $configuration = [
            'Action' => implode(';', $configurationData['switchableControllerActions']),
            'Provider' => implode(';', $configurationData['settings.provider']),
            'StartPoint' => implode(';', $configurationData['settings.startpoint']),
            'Depth' => implode(';', $configurationData['settings.depth']),
        ];

        return $this->renderConfigurationTable($configuration);
    }

    /**
     * Render the configuration table
     *
     * @param $elements
     *
     * @return string
     */
    protected function renderConfigurationTable($elements)
    {
        $table = '<table>';
        foreach ($elements as $key => $value) {
            $table .= '<tr><td><b>' . $key . ': </b></td><td>' . $value . '</td></tr>';
        }
        return $table . '</table>';
    }
}
