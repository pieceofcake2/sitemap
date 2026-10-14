<?php

App::uses('SitemapsController', 'Sitemap.Controller');

class SitemapsControllerTest extends ControllerTestCase
{
    public $fixtures = [
        'plugin.sitemap.sitemap_article',
    ];

    /**
     * @var string
     */
    protected $testApp;

    public function setUp(): void
    {
        parent::setUp();
        Configure::write('Security.salt', 'sitemap-test-salt');
        Configure::write('Security.cipherSeed', '1234567890');
        $this->testApp = CakePlugin::path('Sitemap') . 'tests' . DS . 'test_app' . DS;
        App::build([
            'Model' => [$this->testApp . 'Model' . DS],
            'View' => [$this->testApp . 'View' . DS],
        ], App::PREPEND);
        App::objects('Model', null, false);
        Cache::delete('Sitemap.ModelData.SitemapArticle');
    }

    public function tearDown(): void
    {
        Cache::delete('Sitemap.ModelData.SitemapArticle');
        parent::tearDown();
    }

    /**
     * @param array $pages
     * @return array
     */
    protected function _locations(array $pages): array
    {
        $locations = [];
        foreach ($pages as $page) {
            $locations[] = $page['loc'];
        }

        return $locations;
    }

    public function testDisplay(): void
    {
        $vars = $this->testAction('/sitemap', ['method' => 'get', 'return' => 'vars']);

        $this->assertSame(['SitemapArticle', 'Page'], array_keys($vars['sitemapData']));

        $articles = $vars['sitemapData']['SitemapArticle'];
        $this->assertCount(2, $articles);
        $this->assertStringEndsWith('/sitemap_articles/view/1', $articles[0]['loc']);
        $this->assertSame('2021-01-01 00:00:00', $articles[0]['lastmod']);
        $this->assertSame('daily', $articles[0]['changefreq']);
        $this->assertSame('0.9', $articles[0]['priority']);
        $this->assertStringEndsWith('/sitemap_articles/view/2', $articles[1]['loc']);

        $pages = $vars['sitemapData']['Page'];
        $this->assertCount(3, $pages);
        $locations = $this->_locations($pages);
        $this->assertContains(Router::url(['plugin' => false, 'controller' => 'pages', 'action' => 'display', 'about']), $locations);
        $this->assertContains(Router::url(['plugin' => false, 'controller' => 'pages', 'action' => 'display', 'home']), $locations);
        $this->assertContains(['plugin' => false, 'controller' => 'pages', 'action' => 'index', 'guides'], $locations);
        foreach ($pages as $page) {
            $this->assertSame('daily', $page['changefreq']);
            $this->assertSame('1.0', $page['priority']);
        }
    }

    public function testDisplaySkipsNonModelClasses(): void
    {
        $this->assertContains('SitemapValueObject', App::objects('Model'));

        $vars = $this->testAction('/sitemap', ['method' => 'get', 'return' => 'vars']);

        $this->assertArrayNotHasKey('SitemapValueObject', $vars['sitemapData']);
    }

    public function testDisplayHtml(): void
    {
        $result = $this->testAction('/sitemap', ['method' => 'get', 'return' => 'contents']);

        $this->assertStringContainsString('<h2>SitemapArticle</h2>', $result);
        $this->assertStringContainsString('<h2>Page</h2>', $result);
        $this->assertStringContainsString('/sitemap_articles/view/1</a>', $result);
        $this->assertStringContainsString('<dd>2021-01-01T00:00:00Z</dd>', $result);
        $this->assertStringNotContainsString('SitemapNote', $result);
        $this->assertStringNotContainsString('admin_dashboard', $result);
    }

    public function testDisplayXml(): void
    {
        $result = $this->testAction('/sitemap.xml', ['method' => 'get', 'return' => 'contents']);

        $this->assertStringContainsString('<urlset', $result);
        $this->assertMatchesRegularExpression('#<loc>[^<]*/sitemap_articles/view/2</loc>#', $result);
        $this->assertStringContainsString('<lastmod>2021-02-01T00:00:00Z</lastmod>', $result);
        $this->assertStringContainsString('<priority>0.9</priority>', $result);
        $this->assertStringContainsString('<changefreq>daily</changefreq>', $result);
        $this->assertSame('application/xml', $this->controller->response->type());
    }

    public function testDisplayUsesPagesFromTemplatesDirectory(): void
    {
        App::build([
            'View' => array_values(array_diff(App::path('View'), [$this->testApp . 'View' . DS])),
        ], App::RESET);

        $vars = $this->testAction('/sitemap', ['method' => 'get', 'return' => 'vars']);

        $locations = $this->_locations($vars['sitemapData']['Page']);
        $this->assertSame([Router::url(['plugin' => false, 'controller' => 'pages', 'action' => 'display', 'home'])], $locations);
    }
}
