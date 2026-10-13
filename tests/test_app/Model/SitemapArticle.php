<?php

App::uses('AppModel', 'Model');

/**
 * SitemapArticle
 *
 * Model with the Sitemap behavior attached, used by the tests.
 */
class SitemapArticle extends AppModel
{
    /**
     * Behaviors
     *
     * @var array
     */
    public $actsAs = [
        'Sitemap.Sitemap' => [
            'conditions' => ['SitemapArticle.published' => true],
        ],
    ];

    /**
     * Custom URL builder used as the `loc` callback in the tests
     *
     * @param int|string $id Primary key
     * @return string
     */
    public function articleUrl($id)
    {
        return '/articles/' . $id;
    }
}
