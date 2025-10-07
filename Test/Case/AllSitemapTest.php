<?php
/**
 * Custom test suite to execute all Sitemap Plugin tests.
 *
 * @package Sitemap.Test.Case
 */

use PHPUnit\Framework\TestSuite;

/**
 * AllSitemapTest
 */
class AllSitemapTest extends TestSuite
{
    /**
     * the suites to load
     *
     * @var array
     */
    public static $suites = [
    ];

    /**
     * load the suites
     *
     * @return CakeTestSuite
     */
    public static function suite(): CakeTestSuite
    {
        $path = dirname(__FILE__) . '/';
        $suite = new CakeTestSuite('All Sitemap Tests');

        foreach (self::$suites as $file) {
            if (is_readable($path . $file)) {
                $suite->addTestFile($path . $file);
            }
        }

        return $suite;
    }
}
