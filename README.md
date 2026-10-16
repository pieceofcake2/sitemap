# CakePHP 2 Sitemap

[![GitHub License](https://img.shields.io/github/license/pieceofcake2/sitemap?label=License)](LICENSE)
[![Packagist Version](https://img.shields.io/packagist/v/pieceofcake2/sitemap?label=Packagist)](https://packagist.org/packages/pieceofcake2/sitemap)
![PHP](https://img.shields.io/packagist/dependency-v/pieceofcake2/sitemap/php?logo=php&logoColor=%23FFFFFF&label=PHP&labelColor=%23777BB4&color=%23FFFFFF)
![CakePHP](https://img.shields.io/packagist/dependency-v/pieceofcake2/sitemap/pieceofcake2/cakephp?logo=cakephp&logoColor=%23FFFFFF&label=CakePHP&labelColor=%23D33C43&color=%23FFFFFF)
[![PHPStan](https://img.shields.io/badge/PHPStan-level%205-brightgreen?label=PHPStan)](https://github.com/pieceofcake2/sitemap/actions/workflows/CI.yml)
[![CI](https://img.shields.io/github/actions/workflow/status/pieceofcake2/sitemap/CI.yml?label=CI)](https://github.com/pieceofcake2/sitemap/actions/workflows/CI.yml)
[![Codecov](https://img.shields.io/codecov/c/gh/pieceofcake2/sitemap?label=Coverage)](https://codecov.io/gh/pieceofcake2/sitemap)

pieceofcake2/sitemap adds automatic XML and HTML sitemaps to a CakePHP 2 application. Add the
`Sitemap.Sitemap` behavior to your models and their records are listed at `/sitemap.xml`
and `/sitemap`, together with the static pages of the application.

## Documentation

- Website: https://pieceofcake2.github.io/sitemap/ (日本語: https://pieceofcake2.github.io/sitemap/ja/)
- Markdown: [docs/README.md](docs/README.md) (日本語: [docs/ja/README.md](docs/ja/README.md)) (for AI tools and offline reading)

The Markdown documentation is also included in the Composer package, in the plugin's directory: `plugins/Sitemap/docs/README.md` (the location depends on your `installer-paths`). Install the package with `--dev` if you only need it for development.

## Requirements

* PHP 8.0+
* pieceofcake2/cakephp 2.12+

## Installation

```bash
composer require pieceofcake2/sitemap
```

Then load the plugin with its routes and add the behavior to your models.
See [Installation](docs/README.md#installation) and [Usage](docs/README.md#usage).

## Contributing

See the [contribution guidelines](https://github.com/pieceofcake2/.github/blob/master/CONTRIBUTING.md).

Please report issues on [GitHub](https://github.com/pieceofcake2/sitemap/issues).

## Origin

This package is a maintained fork of
[loadsys/CakePHP-Sitemap](https://github.com/loadsys/CakePHP-Sitemap) (`loadsys/cakephp_sitemap` on
Packagist, last upstream version 1.0.2), originally by Loadsys Web Strategies. It is maintained for
CakePHP 2 on modern PHP by [pieceofcake2](https://github.com/pieceofcake2). Changes from the
original are listed in the
[commit history since the fork](https://github.com/pieceofcake2/sitemap/compare/31a3e4b...1.x).

## License

MIT License. Originally developed by [Loadsys Web Strategies](https://www.loadsys.com). See [LICENSE](LICENSE).
