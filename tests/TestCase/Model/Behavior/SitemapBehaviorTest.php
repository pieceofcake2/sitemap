<?php

App::uses('Model', 'Model');
App::uses('SitemapBehavior', 'Sitemap.Model/Behavior');

/**
 * SitemapBehaviorTest
 *
 * @property Model $Article
 */
class SitemapBehaviorTest extends CakeTestCase
{
    public $fixtures = [
        'plugin.sitemap.sitemap_article',
    ];

    public function setUp(): void
    {
        parent::setUp();
        App::build([
            'Model' => [CakePlugin::path('Sitemap') . 'tests' . DS . 'test_app' . DS . 'Model' . DS],
        ]);
        Cache::delete('Sitemap.ModelData.SitemapArticle');
        $this->Article = ClassRegistry::init('SitemapArticle');
    }

    public function tearDown(): void
    {
        Cache::delete('Sitemap.ModelData.SitemapArticle');
        unset($this->Article);
        ClassRegistry::flush();
        parent::tearDown();
    }

    public function testSetupDefaults(): void
    {
        $settings = $this->Article->Behaviors->Sitemap->settings['SitemapArticle'];

        $this->assertSame('id', $settings['primaryKey']);
        $this->assertSame('buildUrl', $settings['loc']);
        $this->assertSame('modified', $settings['lastmod']);
        $this->assertSame('daily', $settings['changefreq']);
        $this->assertSame('0.9', $settings['priority']);
        $this->assertSame(['SitemapArticle.published' => true], $settings['conditions']);
    }

    public function testBuildUrl(): void
    {
        $result = $this->Article->buildUrl(5);

        $this->assertStringStartsWith('http', $result);
        $this->assertStringEndsWith('/sitemap_articles/view/5', $result);
    }

    public function testGenerateSitemapData(): void
    {
        $result = $this->Article->generateSitemapData();

        $this->assertCount(2, $result);
        $this->assertSame($this->Article->buildUrl(1), $result[0]['loc']);
        $this->assertSame('2021-01-01 00:00:00', $result[0]['lastmod']);
        $this->assertSame('daily', $result[0]['changefreq']);
        $this->assertSame('0.9', $result[0]['priority']);
        $this->assertSame($this->Article->buildUrl(2), $result[1]['loc']);
    }

    public function testGenerateSitemapDataWithCustomSettings(): void
    {
        $this->Article->Behaviors->load('Sitemap.Sitemap', [
            'loc' => 'articleUrl',
            'lastmod' => 'updated',
            'changefreq' => 'weekly',
            'conditions' => [],
        ]);

        $result = $this->Article->generateSitemapData();

        $this->assertCount(3, $result);
        $this->assertSame([
            'loc' => '/articles/3',
            'lastmod' => '2020-03-02 03:04:05',
            'changefreq' => 'weekly',
            'priority' => '0.9',
        ], $result[2]);
    }

    public function testGenerateSitemapDataWithDisabledFields(): void
    {
        $this->Article->Behaviors->load('Sitemap.Sitemap', [
            'loc' => 'articleUrl',
            'lastmod' => false,
            'changefreq' => false,
        ]);

        $result = $this->Article->generateSitemapData();

        $this->assertSame([
            ['loc' => '/articles/1', 'priority' => '0.9'],
            ['loc' => '/articles/2', 'priority' => '0.9'],
        ], $result);
    }

    public function testGenerateSitemapDataIsCached(): void
    {
        $first = $this->Article->generateSitemapData();
        $this->assertSame($first, Cache::read('Sitemap.ModelData.SitemapArticle'));

        $this->Article->deleteAll(['SitemapArticle.id >' => 0], false);
        $this->assertSame($first, $this->Article->generateSitemapData());

        Cache::delete('Sitemap.ModelData.SitemapArticle');
        $this->assertSame([], $this->Article->generateSitemapData());
    }
}
