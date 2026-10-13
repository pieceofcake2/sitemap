<?php
/**
 * Displays the sitemap file
 *
 * @package Sitemap.Controller
 */
App::uses('SitemapAppController', 'Sitemap.Controller');
App::uses('PagesIterator', 'Sitemap.Lib/Iterators');

/**
 * SitemapsController
 */
class SitemapsController extends SitemapAppController
{
    /**
     * helpers - array of helpers
     *
     * @var array
     */
    public $helpers = [
        'Time',
        'Html',
        'Cache',
    ];

    /**
     * components - array of components
     *
     * @var array
     */
    public $components = [
        'RequestHandler',
        'Auth',
    ];

    /**
     * cacheAction - view cache timing
     *
     * @var array
     */
    public $cacheAction = [
        'display' => 43200,
    ];

    /**
     * beforeFilter - beforeFilter callback
     *
     * @return void
     */
    public function beforeFilter()
    {
        parent::beforeFilter();
        $this->Auth->allow(['display']);
    }

    /**
     * beforeRender - beforeRender callback
     *
     * @return void
     */
    public function beforeRender()
    {
        parent::beforeRender();
    }

    /**
     * display - display the sitemap
     *
     * @return void
     */
    public function display()
    {
        $sitemapData = [];

        //Generate a list of Models in the App
        $listOfModels = $this->_generateListOfModels();

        //foreach model
        foreach ($listOfModels as $model) {
            App::uses($model, 'Model');

            // We need to load the class
            $newModel = ClassRegistry::init($model);

            if ($newModel->Behaviors->loaded('Sitemap')) {
                $response = $newModel->generateSitemapData();
                $sitemapData[$newModel->name] = $response;
            }
            unset($newModel);
        }

        //Generate Sitemap of Static Pages
        $sitemapData['Page'] = $this->_generateListOfStaticPages();

        $this->set('sitemapData', $sitemapData);
    }

    /**
     * _generateListOfModels - generate the list of models
     *
     * @return array
     */
    protected function _generateListOfModels()
    {
        //Generate list of Models
        $appModelClasses = App::objects('Model');

        $listOfModels = [];

        //Foreach Model
        foreach ($appModelClasses as $modelClass) {
            if ($modelClass != 'AppModel') {
                // Load the Model
                App::import('Model', str_replace('Model', '', $modelClass));
                $listOfModels[] = $modelClass;
            }
        }

        return $listOfModels;
    }

    /**
     * _findPagesPath - find the directory containing the static pages
     *
     * Looks through all View paths (e.g. `src/View/` and `templates/`).
     *
     * @return string|null
     */
    protected function _findPagesPath()
    {
        foreach (App::path('View') as $path) {
            if (is_dir($path . 'Pages')) {
                return $path . 'Pages' . DS;
            }
        }

        return null;
    }

    /**
     * _generateListOfStaticPages - generate the list of static pages and the sitemap data
     *
     * @return array
     */
    protected function _generateListOfStaticPages()
    {
        $pagesSitemap = [];

        $pagesPath = $this->_findPagesPath();
        if ($pagesPath === null) {
            return $pagesSitemap;
        }

        $pages = new PagesIterator($pagesPath, [], $this->request->webroot);
        $pagesArray = iterator_to_array($pages);

        foreach ($pagesArray as $key => $page) {
            $pagesSitemap[$key] = [];

            $pagesSitemap[$key]['loc'] = $page['url'];
            $pagesSitemap[$key]['lastmod'] = $page['modified'];
            $pagesSitemap[$key]['changefreq'] = 'daily';
            $pagesSitemap[$key]['priority'] = '1.0';
        }

        return $pagesSitemap;
    }
}
