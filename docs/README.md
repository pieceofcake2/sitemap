# Sitemap Documentation

pieceofcake2/sitemap adds an XML sitemap (`/sitemap.xml`) and an HTML sitemap (`/sitemap`)
to a CakePHP 2 application. The sitemaps list the records of the models that use the
`Sitemap.Sitemap` behavior and the static pages of the application.

- [Installation](#installation)
- [Usage](#usage)
- [Behavior Options](#behavior-options)
- [Static Pages](#static-pages)
- [Caching](#caching)
- [Limitations](#limitations)

## Installation

Install the plugin with [Composer](https://getcomposer.org/):

```bash
composer require pieceofcake2/sitemap
```

The plugin has the `cakephp-plugin` type, so it is installed into your plugins directory
by `composer/installers`. Set `installer-paths` in your application's `composer.json` so it
is installed into the right place:

| Layout | `installer-paths` |
| --- | --- |
| Modern (`config/`, `src/`, `templates/`, `plugins/`) | `"plugins/{$name}/": ["type:cakephp-plugin"]` |
| Legacy (`app/Config/`, `app/Plugin/`) | `"app/Plugin/{$name}/": ["type:cakephp-plugin"]` |

Load the plugin with its routes in `config/bootstrap.php` (legacy layout:
`app/Config/bootstrap.php`):

```php
CakePlugin::load(['Sitemap' => ['routes' => true]]);
```

The plugin's routes connect `/sitemap` and enable the `xml` extension, so the sitemaps are
available at `/sitemap` and `/sitemap.xml`.

## Usage

Add the behavior to each model whose records should be listed in the sitemap:

```php
class Post extends AppModel
{
    public $actsAs = [
        'Sitemap.Sitemap' => [
            'primaryKey' => 'id',
            'loc' => 'buildUrl',
            'lastmod' => 'modified',
            'changefreq' => 'daily',
            'priority' => '0.9',
            'conditions' => [],
        ],
    ];
}
```

All options are optional; the values above are the defaults.

## Behavior Options

| Option | Default | Description |
| --- | --- | --- |
| `primaryKey` | `'id'` | Field whose value is passed to the `loc` method. |
| `loc` | `'buildUrl'` | Name of the model method that returns the URL of a record. It is called with the value of the `primaryKey` field. The default `buildUrl` returns the full URL of the `view` action of the model's controller, e.g. `/posts/view/1`. |
| `lastmod` | `'modified'` | Field with the last modification date, or `false` to omit `<lastmod>`. |
| `changefreq` | `'daily'` | Change frequency of all records of the model, or `false` to omit it. |
| `priority` | `'0.9'` | Priority of all records of the model, or `false` to omit it. |
| `conditions` | `[]` | Find conditions to limit the records listed in the sitemap. |

To build the URLs yourself, add a method to the model and set its name as `loc`:

```php
class Post extends AppModel
{
    public $actsAs = [
        'Sitemap.Sitemap' => [
            'loc' => 'sitemapUrl',
            'conditions' => ['Post.published' => true],
        ],
    ];

    /**
     * Returns the URL of a post for the sitemap.
     *
     * @param string|int $id Post ID
     * @return string
     */
    public function sitemapUrl($id)
    {
        $slug = $this->field('slug', ['Post.id' => $id]);

        return Router::url(['controller' => 'posts', 'action' => 'view', $slug], true);
    }
}
```

## Static Pages

The `.ctp` files in the `Pages` directory of your view paths (`templates/Pages/`, legacy
layout: `app/View/Pages/`) are listed as well, with the priority `1.0` and the change frequency
`daily`. The XML sitemap also always contains the home page (`/`).

## Caching

- The records of each model are cached with the `default` cache configuration under the key
  `Sitemap.ModelData.<Model>`. Delete the key (or clear the cache) to rebuild the sitemap of a
  model after its records change.
- The sitemap pages use view caching for 12 hours (`$cacheAction`), which takes effect when
  `Cache.check` is enabled in your `core.php`.

## Limitations

- Only the models of the application are listed, not the models of plugins.
- The HTML sitemap is a definition list (`<dl>`) per model.
