<?php

App::uses('PagesIterator', 'Sitemap.Lib/Iterators');

class PagesIteratorTest extends CakeTestCase
{
    /**
     * @var string
     */
    protected $path;

    public function setUp(): void
    {
        parent::setUp();
        $this->path = CakePlugin::path('Sitemap') . 'tests' . DS . 'test_app' . DS . 'View' . DS . 'Pages' . DS;
    }

    /**
     * @param PagesIterator $iterator
     * @return array<string, array>
     */
    protected function _pages(PagesIterator $iterator): array
    {
        $pages = [];
        foreach ($iterator as $page) {
            $pages[$page['basename']] = $page;
        }
        ksort($pages);

        return $pages;
    }

    public function testPages(): void
    {
        $pages = $this->_pages(new PagesIterator($this->path, [], '/'));

        $this->assertSame(['about.ctp', 'guides', 'home.ctp'], array_keys($pages));

        $about = $pages['about.ctp'];
        $this->assertSame('about.ctp', $about['filename']);
        $this->assertSame('About', $about['title']);
        $this->assertSame(Router::url(['plugin' => false, 'controller' => 'pages', 'action' => 'display', 'about']), $about['url']);
        $this->assertSame(filesize($this->path . 'about.ctp'), $about['bytes']);
        $this->assertSame(filemtime($this->path . 'about.ctp'), $about['modified']);
        $this->assertArrayNotHasKey('children', $about);
    }

    public function testDirectory(): void
    {
        $pages = $this->_pages(new PagesIterator($this->path, [], '/'));

        $guides = $pages['guides'];
        $this->assertSame('Guides', $guides['title']);
        $this->assertSame(['plugin' => false, 'controller' => 'pages', 'action' => 'index', 'guides'], $guides['url']);
        $this->assertSame(1, $guides['children']);
    }

    public function testDepth(): void
    {
        $pages = $this->_pages(new PagesIterator($this->path . 'guides' . DS, ['guides'], '/'));

        $this->assertSame(['intro.ctp'], array_keys($pages));
        $this->assertSame('guides/intro.ctp', $pages['intro.ctp']['filename']);
        $this->assertSame(Router::url(['plugin' => false, 'controller' => 'pages', 'action' => 'display', 'guides', 'intro']), $pages['intro.ctp']['url']);
    }

    public function testAllowedExtensions(): void
    {
        $pages = $this->_pages(new PagesIterator($this->path, [], '/', ['txt']));

        $this->assertSame(['guides', 'notes.txt'], array_keys($pages));
    }
}
