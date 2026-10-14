# CakePHP 2 Sitemap

[![GitHub License](https://img.shields.io/github/license/pieceofcake2/sitemap?label=License)](LICENSE)
[![Packagist Version](https://img.shields.io/packagist/v/pieceofcake2/sitemap?label=Packagist)](https://packagist.org/packages/pieceofcake2/sitemap)
![PHP](https://img.shields.io/packagist/dependency-v/pieceofcake2/sitemap/php?logo=php&logoColor=%23FFFFFF&label=PHP&labelColor=%23777BB4&color=%23FFFFFF)
[![PHPStan](https://img.shields.io/badge/PHPStan-level%205-brightgreen?label=PHPStan)](https://github.com/pieceofcake2/sitemap/actions/workflows/CI.yml)
![CakePHP](https://img.shields.io/packagist/dependency-v/pieceofcake2/sitemap/pieceofcake2/cakephp?logo=cakephp&logoColor=%23FFFFFF&label=CakePHP&labelColor=%23D33C43&color=%23FFFFFF)
[![CI](https://img.shields.io/github/actions/workflow/status/pieceofcake2/sitemap/CI.yml?label=CI)](https://github.com/pieceofcake2/sitemap/actions/workflows/CI.yml)
[![Codecov](https://img.shields.io/codecov/c/gh/pieceofcake2/sitemap?label=Coverage)](https://codecov.io/gh/pieceofcake2/sitemap)

pieceofcake2/sitemap adds automatic XML and HTML sitemaps to a CakePHP 2 application. Add the
`Sitemap.Sitemap` behavior to your models and their records are listed at `/sitemap.xml`
and `/sitemap`, together with the static pages of the application.

## Requirements

* PHP 8.0+
* pieceofcake2/cakephp 2.12+

## Installation

```bash
composer require pieceofcake2/sitemap
```

Then load the plugin with its routes and add the behavior to your models.
See [Installation](docs/README.md#installation) and [Usage](docs/README.md#usage).

## Documentation

Documentation: https://pieceofcake2.github.io/sitemap/

## Contributing

See the [contribution guidelines](https://github.com/pieceofcake2/.github/blob/master/CONTRIBUTING.md).

Please report issues on [GitHub](https://github.com/pieceofcake2/sitemap/issues).

## License

MIT License. Originally developed by [Loadsys Web Strategies](https://www.loadsys.com). See [LICENSE](LICENSE).
