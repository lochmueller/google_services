<?php
/**
 * SitemapProviderTest
 */

namespace FRUIT\GoogleServices\Tests\Unit\Service;

use FRUIT\GoogleServices\Service\SitemapProvider;
use TYPO3\CMS\Core\Tests\UnitTestCase;

/**
 * SitemapProviderTest
 */
class SitemapProviderTest extends UnitTestCase
{

    /**
     * @test
     */
    public function testValidaNameConversion()
    {
        $className = 'Tx_ExtensionName_Domain_Model_Old_Name';
        $extensionName = SitemapProvider::getExtensionNameByClassName($className);
        $this->assertSame($extensionName, 'ExtensionName');
    }
}
