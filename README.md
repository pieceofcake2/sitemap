# CakePHP 2 Sitemap

[![GitHub License](https://img.shields.io/github/license/pieceofcake2/sitemap?label=License)](LICENSE)
[![Packagist Version](https://img.shields.io/packagist/v/pieceofcake2/sitemap?label=Packagist)](https://packagist.org/packages/pieceofcake2/sitemap)
![PHP](https://img.shields.io/packagist/dependency-v/pieceofcake2/sitemap/php?logo=php&logoColor=%23FFFFFF&label=PHP&labelColor=%23777BB4&color=%23FFFFFF)
![CakePHP](https://img.shields.io/packagist/dependency-v/pieceofcake2/sitemap/pieceofcake2/cakephp?logo=cakephp&logoColor=%23FFFFFF&label=CakePHP&labelColor=%23D33C43&color=%23FFFFFF)
[![CI](https://img.shields.io/github/actions/workflow/status/pieceofcake2/sitemap/CI.yml?label=CI)](https://github.com/pieceofcake2/sitemap/actions/workflows/CI.yml)
[![Codecov](https://img.shields.io/codecov/c/gh/pieceofcake2/sitemap?label=Coverage)](https://codecov.io/gh/pieceofcake2/sitemap)

__This is forked for CakePHP2.__

A CakePHP 2.x Plugin for adding automatic XML and HTML Sitemaps to an CakePHP app

## Background

* Only generates a sitemap currently for models in the core App, not in Plugins.
* Generates an HTML list using a dl list.
* Generates an sitemap.xml file as well.
* View caching used for the HTML files.
* Allows for setting a custom callback function to build urls.

## Requirements

* PHP 8.0+
* CakePHP 2.10+

## Installation

### Composer

````bash
$ composer require pieceofcake2/sitemap
````

## Usage

* Add this this line to your `bootstrap.php`:

````php
CakePlugin::load(['Sitemap' => ['routes' => true]]);
````

* Add the behavior to the model desired to generate a sitemap for that model

````php
public $actsAs = [
    'Sitemap.Sitemap' => [
        'primaryKey' => 'id', // Default primary key field
        'loc' => 'buildUrl', // Default function called that builds a url, passes parameters (Model $Model, $primaryKey)
        'lastmod' => 'modified', // Default last modified field, can be set to FALSE if no field for this
        'changefreq' => 'daily', // Default change frequency applied to all model items of this type, can be set to FALSE to pass no value
        'priority' => '0.9', // Default priority applied to all model items of this type, can be set to FALSE to pass no value
        'conditions' => [], // Conditions to limit or control the returned results for the sitemap
    ]
];
````

* Sitemap should now be visible at /sitemap and /sitemap.xml

## Contributing

### Reporting Issues

Please use [GitHub Isuses](https://github.com/pieceofcake2/sitemap/issues) for listing any known defects or issues.

## License ##

[MIT](./LICENSE)


## Copyright ##

[Loadsys Web Strategies](https://www.loadsys.com) 2016
