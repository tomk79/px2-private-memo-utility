<?php
namespace tomk79\pickles2\px2PrivateMemoUtility;

/**
 * px2-private-memo-utility
 */
class main{

	/** Picklesオブジェクト */
	private $px;

	/** プラグイン設定 */
	private $options;

	/**
	 * processor
	 *
	 * @param object $px Picklesオブジェクト
	 * @param object $options プラグイン設定
	 */
	static public function processor( $px = null, $options = null ){
		if( count(func_get_args()) <= 1 ){
			return __CLASS__.'::'.__FUNCTION__.'('.( is_array($px) ? json_encode($px) : '' ).')';
		}

		$options = (object) $options;
		if( !isset($options->auto_link_target_blank) ){ $options->auto_link_target_blank = null; }
		if( !isset($options->hide_referrer) ){ $options->hide_referrer = null; }
		if( !isset($options->allow_highlight) ){ $options->allow_highlight = null; }

		$main = new self( $px, $options );

		$main->process_contents();

		return;
	}


	/**
	 * Constructor
	 *
	 * @param object $px $pxオブジェクト
	 * @param object $options プラグイン設定
	 */
	private function __construct( $px, $options ){
		$this->px = $px;
		$this->options = $options;
	}

	/**
	 * サーバーを起動する
	 */
	private function process_contents(){
		if( !$this->options->auto_link_target_blank && !$this->options->hide_referrer && !$this->options->allow_highlight ){
			return;
		}

		require_once(__DIR__.'/simple_html_dom.php');


        foreach( $this->px->bowl()->get_keys() as $key ){
            $src = $this->px->bowl()->get_clean( $key );

			$detect_encoding = mb_detect_encoding(''.$src);

			$html = str_get_html(
				mb_convert_encoding( $src, DEFAULT_TARGET_CHARSET, $detect_encoding ) ,
				false, // $lowercase
				false, // $forceTagsClosed
				DEFAULT_TARGET_CHARSET, // $target_charset
				false, // $stripRN
				DEFAULT_BR_TEXT, // $defaultBRText
				DEFAULT_SPAN_TEXT // $defaultSpanText
			);
			if($html === false){
				// HTMLパースに失敗した場合、無加工のまま返す。
	            $this->px->bowl()->replace( $src, $key );
				$this->px->error('HTML Parse ERROR. $src size '.strlen(''.$src).' byte(s) given; '.__FILE__.' ('.__LINE__.')');
				continue;
			}


			if( $this->options->auto_link_target_blank ){
				// --------------------------------------
				// target=_blank の自動付与
				$ret = $html->find('a[href]');
				foreach( $ret as $retRow ){
					$href = $retRow->getAttribute('href');
					$deftarget = $retRow->getAttribute('target');
					if( $deftarget ){
						// target属性が明示されている場合は、それを尊重する
						continue;
					}

					if( preg_match( '/^(?:https?\:\/\/)/i', $href ) ){
						$retRow->setAttribute('target', '_blank');
					}
				}
			}

			if( $this->options->hide_referrer ){
				// --------------------------------------
				// noopener noreferrer の自動付与
				$ret = $html->find('a,area,form');
				foreach( $ret as $retRow ){
					$deftarget = $retRow->getAttribute('target');
					if( $this->options->hide_referrer === 'target_blank' && $deftarget != '_blank' ){
						continue;
					}
					$retRow->setAttribute('rel', 'noopener noreferrer');
				}
			}

			$src = $html->outertext;
			$src = mb_convert_encoding( $src, $detect_encoding );
            $this->px->bowl()->replace( $src, $key );
        }


		if( $this->options->allow_highlight ){
			// --------------------------------------
			// highlighter を挿入する
			// highlight.js
			// https://highlightjs.org/
			// Thank you for Ivan Sagalaev and contributors!
			ob_start(); ?>
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/highlight.js/11.5.1/styles/atom-one-light.min.css">
<script src="//cdnjs.cloudflare.com/ajax/libs/highlight.js/11.5.1/highlight.min.js"></script>
<script>
hljs.highlightAll();
</script>
<?php
			$highlighter = ob_get_clean();
            $src = $this->px->bowl()->get( 'main' );
			if( preg_match('/(?:\<\/body\>)/', $src) ){
				// bodyの閉じタグを探す。
				// 見つかった場合、theme適用後と判断して、閉じタグ直前に追加する
				$src = preg_replace('/(?:\<\/body\>)/', $highlighter, $src);
				$this->px->bowl()->replace($src, 'main');
			}else{
				// </body>が見つからない場合は、theme適用前とみなす。
				// foot に挿入する。
				$this->px->bowl()->put( $highlighter, 'foot' );
			}

		}


		return;
	}

}
