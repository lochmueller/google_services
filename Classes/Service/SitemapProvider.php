<?php

/**
 * Sitemap provider
 *
 * @author Tim Lochmüller
 */

namespace FRUIT\GoogleServices\Service;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Mvc\Exception\InvalidArgumentValueException;
use TYPO3\CMS\Extbase\Object\ObjectManager;

/**
 * Description of SitemapProvider
 */
class SitemapProvider
{

    /**
     * Provider Storage
     *
     * @var Array
     */
    private static $provider = array();

    /**
     * Add a Sitemap Provider
     *
     * @param string $className
     */
    public static function addProvider($className)
    {
        self::$provider[$className] = $className;
    }

    /**
     * Get all Providers
     *
     * @return array
     */
    public static function getProviders()
    {
        return self::$provider;
    }

    /**
     * Get a provider
     *
     * @param string $name
     *
     * @throws InvalidArgumentValueException
     * @return SitemapProviderInterface
     */
    public static function getProvider($name)
    {
        if (!isset(self::$provider[$name])) {
            throw new InvalidArgumentValueException($name . ' not exists');
        }
        $obj = new ObjectManager();
        return $obj->get($name);
    }

    /**
     * @param $params
     * @param $ref
     */
    public function flexformSelection(&$params, &$ref)
    {
        $providers = self::getProviders();
        $params['items'] = array();


        foreach ($providers as $provider) {
            $parts = self::getExtensionNameByClassName($provider);
            $extensionName = $parts['extensionName'];
            $params['items'][] = array(
                $provider,
                $provider,
                'EXT:' . GeneralUtility::camelCaseToLowerCaseUnderscored($extensionName) . '/ext_icon.gif',
            );
        }
    }

    /**
     * @param $className
     *
     * @return array
     */
    static protected function getExtensionNameByClassName($className)
    {
        $matches = array();
        if (strpos($className, '\\') !== false) {
            if (substr($className, 0, 9) === 'TYPO3\\CMS') {
                $extensionName = '^(?P<vendorName>[^\\\\]+\\\[^\\\\]+)\\\(?P<extensionName>[^\\\\]+)';
            } else {
                $extensionName = '^(?P<vendorName>[^\\\\]+)\\\\(?P<extensionName>[^\\\\]+)';
            }
            preg_match('/' . $extensionName . '\\\\.*$/ix', $className, $matches);
        } else {
            preg_match('/^Tx_(?P<extensionName>[^_]+)_.*$/ix', $className, $matches);
        }
        return $matches;
    }

}