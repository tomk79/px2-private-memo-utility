# px2-private-memo-utility

[Pickles 2](https://pickles2.pxt.jp/) で個人メモを書き留める用途に便利な機能を提供します。


## Usage - 使い方

### インストール

```
composer require tomk79/px2-private-memo-utility;
```

### セットアップ

```php
$conf->funcs->processor->html = [

    /* 〜〜 中略 〜〜 */

    // Private Memo Utility: コンテンツ加工処理
    \tomk79\pickles2\px2PrivateMemoUtility\main::processor( [
        "auto_link_target_blank" => true,
        "hide_referrer" => true,
        "allow_highlight" => true,
    ] ),

    /* 〜〜 中略 〜〜 */
];
```

## オプション

### auto_link_target_blank

a要素に、`target=_blank` を自動的に付与します。

a要素の `href` 属性値が `http://` または `https://` から始まる場合に、 `target=_blank` を付与します。

予め `target` 属性がセットされている a要素には影響しません。


### hide_referrer

a要素、area要素、form要素に、 `rel="noopener noreferrer"` を付与します。

`hide_referrer => target_blank` を設定した場合は、 `target` 属性が `_blank` となっている要素にのみ影響します。


### allow_highlight

[hightlight.js](https://highlightjs.org/) を挿入します。


## 更新履歴 - Change log

### tomk79/px2-private-memo-utility v0.1.0 (2022年6月18日)

- Initial Release



## ライセンス - License

MIT License
https://opensource.org/licenses/mit-license.php


## 作者 - Author

- Tomoya Koyanagi <tomk79@gmail.com>
- website: <https://www.pxt.jp/>
- Twitter: @tomk79 <https://twitter.com/tomk79/>
