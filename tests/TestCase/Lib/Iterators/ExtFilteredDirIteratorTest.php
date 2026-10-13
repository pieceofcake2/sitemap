<?php

App::uses('ExtFilteredDirIterator', 'Sitemap.Lib/Iterators');

class ExtFilteredDirIteratorTest extends CakeTestCase
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
     * @param ExtFilteredDirIterator $iterator
     * @return array<string>
     */
    protected function _names(ExtFilteredDirIterator $iterator): array
    {
        $names = [];
        foreach ($iterator as $fileinfo) {
            $names[] = $fileinfo->getFilename();
        }
        sort($names);

        return $names;
    }

    public function testWithoutAllowedExtensions(): void
    {
        $result = $this->_names(new ExtFilteredDirIterator($this->path));

        $this->assertSame(['about.ctp', 'admin_dashboard.ctp', 'guides', 'home.ctp', 'notes.txt'], $result);
    }

    public function testWithAllowedExtensions(): void
    {
        $result = $this->_names(new ExtFilteredDirIterator($this->path, ['ctp']));

        $this->assertSame(['about.ctp', 'admin_dashboard.ctp', 'guides', 'home.ctp'], $result);
    }

    public function testWithUnmatchedExtensions(): void
    {
        $result = $this->_names(new ExtFilteredDirIterator($this->path, ['md']));

        $this->assertSame(['guides'], $result);
    }

    public function testInvalidPath(): void
    {
        $this->expectException(UnexpectedValueException::class);

        new ExtFilteredDirIterator($this->path . 'missing');
    }
}
