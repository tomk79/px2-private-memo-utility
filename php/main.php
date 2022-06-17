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
			}

			if( $this->options->allow_highlight ){
			}


			$src = $html->outertext;
			$src = mb_convert_encoding( $src, $detect_encoding );
            $this->px->bowl()->replace( $src, $key );
        }

		return;
	}

}
