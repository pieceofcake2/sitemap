<?php

/**
 * SitemapArticleFixture
 */
class SitemapArticleFixture extends CakeTestFixture
{
    /**
     * Fields
     *
     * @var array
     */
    public $fields = [
        'id' => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'title' => ['type' => 'string', 'null' => false, 'default' => null],
        'published' => ['type' => 'boolean', 'null' => false, 'default' => '1'],
        'updated' => ['type' => 'datetime', 'null' => true, 'default' => null],
        'modified' => ['type' => 'datetime', 'null' => true, 'default' => null],
    ];

    /**
     * Records
     *
     * @var array
     */
    public $records = [
        [
            'id' => 1,
            'title' => 'First Article',
            'published' => 1,
            'updated' => '2020-01-02 03:04:05',
            'modified' => '2021-01-01 00:00:00',
        ],
        [
            'id' => 2,
            'title' => 'Second Article',
            'published' => 1,
            'updated' => '2020-02-02 03:04:05',
            'modified' => '2021-02-01 00:00:00',
        ],
        [
            'id' => 3,
            'title' => 'Draft Article',
            'published' => 0,
            'updated' => '2020-03-02 03:04:05',
            'modified' => '2021-03-01 00:00:00',
        ],
    ];
}
