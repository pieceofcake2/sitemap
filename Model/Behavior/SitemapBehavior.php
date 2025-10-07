<?php

class SitemapBehavior extends ModelBehavior
{
    /**
     * _CacheKey - Cache Key
     *
     * @var string
     */
    protected $_CacheKey = 'Sitemap.ModelData.';

    /**
     * setup
     *
     * @param Model $model
     * @param array $settings
     * @return void
     */
    public function setup(Model $model, $settings = [])
    {
        if (!isset($this->settings[$model->alias])) {
            $this->settings[$model->alias] = [
                'primaryKey' => 'id',
                'loc' => 'buildUrl',
                'lastmod' => 'modified',
                'changefreq' => 'daily',
                'priority' => '0.9',
                'conditions' => [],
            ];
        }
        $this->settings[$model->alias] = array_merge($this->settings[$model->alias], $settings);
    }

    /**
     * buildUrl - basic build URL function for the model behavior, basic URL using action => 'view'
     *
     * @param Model $Model
     * @param string $primaryKey
     * @return string
     */
    public function buildUrl(Model $Model, $primaryKey)
    {
        return Router::url(['plugin' => null, 'controller' => Inflector::tableize($Model->name), 'action' => 'view', $primaryKey], true);
    }

    /**
     * generateSitemapData - generate the sitemap data, attempting to hit the cache for this data
     *
     * @param Model $model
     * @return mixed|array
     */
    public function generateSitemapData(Model $model)
    {
        //Attempt to hit the Model Cache for data
        $sitemapData = Cache::read($this->_CacheKey . $model->name);

        if ($sitemapData !== false) {
            return $sitemapData;
        }

        //Load the Model Data
        $modelData = $model->find('all', [
            'conditions' => $this->settings[$model->alias]['conditions'],
            'recursive' => -1,
        ]);

        //Build the sitemap elements
        $sitemapData = $this->_buildSitemapElements($model, $modelData);

        //Write to the Cache
        Cache::write($this->_CacheKey . $model->name, $sitemapData);

        return $sitemapData;
    }

    /**
     * _buildSitemapElements - build the sitemap elements
     *
     * @param Model $Model
     * @param array $modelData
     * @return array
     */
    protected function _buildSitemapElements(Model $Model, $modelData)
    {
        $sitemapData = [];

        //Loop through the Model data and create the array of elements for the sitemap
        foreach ($modelData as $key => $data) {
            $sitemapData[$key] = [];

            $sitemapData[$key]['loc'] = call_user_func([$Model, $this->settings[$Model->alias]['loc']], $data[$Model->alias][$this->settings[$Model->alias]['primaryKey']]);

            if ($this->settings[$Model->alias]['lastmod'] !== false) {
                $sitemapData[$key]['lastmod'] = $data[$Model->alias][$this->settings[$Model->alias]['lastmod']];
            }

            if ($this->settings[$Model->alias]['changefreq'] !== false) {
                $sitemapData[$key]['changefreq'] = $this->settings[$Model->alias]['changefreq'];
            }

            if ($this->settings[$Model->alias]['priority'] !== false) {
                $sitemapData[$key]['priority'] = $this->settings[$Model->alias]['priority'];
            }
        }

        return $sitemapData;
    }
}
