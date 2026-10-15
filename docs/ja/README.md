# Sitemap ドキュメント

pieceofcake2/sitemap は、CakePHP 2 アプリケーションに XML サイトマップ (`/sitemap.xml`) と
HTML サイトマップ (`/sitemap`) を追加します。サイトマップには、`Sitemap.Sitemap` ビヘイビアを使う
モデルのレコードと、アプリケーションの静的ページが一覧表示されます。

- [インストール](#installation)
- [使い方](#usage)
- [ビヘイビアのオプション](#behavior-options)
- [静的ページ](#static-pages)
- [キャッシュ](#caching)
- [制限事項](#limitations)

<a id="installation"></a>

## インストール

[Composer](https://getcomposer.org/) でプラグインをインストールします。

```bash
composer require pieceofcake2/sitemap
```

プラグインのタイプは `cakephp-plugin` なので、`composer/installers` によってプラグインディレクトリに
インストールされます。正しい場所にインストールされるように、アプリケーションの `composer.json` で
`installer-paths` を設定してください。

| レイアウト | `installer-paths` |
| --- | --- |
| モダン (`config/`, `src/`, `templates/`, `plugins/`) | `"plugins/{$name}/": ["type:cakephp-plugin"]` |
| レガシー (`app/Config/`, `app/Plugin/`) | `"app/Plugin/{$name}/": ["type:cakephp-plugin"]` |

`config/bootstrap.php` (レガシーレイアウトでは `app/Config/bootstrap.php`) で、プラグインを
ルートとともに読み込みます。

```php
CakePlugin::load(['Sitemap' => ['routes' => true]]);
```

プラグインのルートは `/sitemap` を接続し、`xml` 拡張子を有効にします。そのため、サイトマップは
`/sitemap` と `/sitemap.xml` で利用できます。

<a id="usage"></a>

## 使い方

サイトマップにレコードを載せたいモデルごとに、ビヘイビアを追加します。

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

オプションはすべて省略可能で、上の値がデフォルトです。

<a id="behavior-options"></a>

## ビヘイビアのオプション

| オプション | デフォルト | 説明 |
| --- | --- | --- |
| `primaryKey` | `'id'` | `loc` のメソッドに値を渡すフィールド。 |
| `loc` | `'buildUrl'` | レコードの URL を返すモデルメソッドの名前。`primaryKey` のフィールドの値を引数に呼び出されます。デフォルトの `buildUrl` は、モデルに対応するコントローラーの `view` アクションの完全な URL (例: `/posts/view/1`) を返します。 |
| `lastmod` | `'modified'` | 最終更新日時のフィールド。`false` にすると `<lastmod>` を出力しません。 |
| `changefreq` | `'daily'` | そのモデルの全レコードの更新頻度。`false` にすると出力しません。 |
| `priority` | `'0.9'` | そのモデルの全レコードの優先度。`false` にすると出力しません。 |
| `conditions` | `[]` | サイトマップに載せるレコードを絞り込む find の条件。 |

URL を自分で組み立てるには、モデルにメソッドを追加し、その名前を `loc` に設定します。

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

<a id="static-pages"></a>

## 静的ページ

ビューパスの `Pages` ディレクトリ (`templates/Pages/`、レガシーレイアウトでは `app/View/Pages/`) にある
`.ctp` ファイルも、優先度 `1.0`、更新頻度 `daily` で一覧に含まれます。また、XML サイトマップには
常にホームページ (`/`) が含まれます。

<a id="caching"></a>

## キャッシュ

- 各モデルのレコードは、`default` のキャッシュ設定を使って `Sitemap.ModelData.<Model>` というキーで
  キャッシュされます。モデルのレコードが変わった後にそのモデルのサイトマップを作り直すには、
  このキーを削除 (またはキャッシュをクリア) してください。
- サイトマップのページはビューキャッシュを 12 時間使います (`$cacheAction`)。これは `core.php` で
  `Cache.check` が有効なときに機能します。

<a id="limitations"></a>

## 制限事項

- 一覧に含まれるのはアプリケーションのモデルだけで、プラグインのモデルは含まれません。
- HTML サイトマップは、モデルごとの定義リスト (`<dl>`) です。

<a id="origin"></a>

## 由来

このパッケージは
[loadsys/CakePHP-Sitemap](https://github.com/loadsys/CakePHP-Sitemap) (Packagist では
`loadsys/cakephp_sitemap`、アップストリームの最終バージョンは 1.0.2) のメンテナンスされたフォークで、
元の作者は Loadsys Web Strategies です。[pieceofcake2](https://github.com/pieceofcake2) が、
モダンな PHP で動く CakePHP 2 向けにメンテナンスしています。オリジナルからの変更点は
[フォーク以降のコミット履歴](https://github.com/pieceofcake2/sitemap/compare/31a3e4b...1.x)
で確認できます。
