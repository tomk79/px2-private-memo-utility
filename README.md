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


## 更新履歴 - Change log

### tomk79/px2-private-memo-utility v0.1.0 (リリース日未定)

- Initial Release



## ライセンス - License

MIT License
https://opensource.org/licenses/mit-license.php


## 作者 - Author

- Tomoya Koyanagi <tomk79@gmail.com>
- website: <https://www.pxt.jp/>
- Twitter: @tomk79 <https://twitter.com/tomk79/>
