<?php

App::uses('AppModel', 'Model');

/**
 * SitemapNote
 *
 * Model without the Sitemap behavior, used by the tests.
 */
class SitemapNote extends AppModel
{
    /**
     * Table
     *
     * @var string|false
     */
    public $useTable = false;
}
