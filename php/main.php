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
	 * entry
	 *
	 * @param object $px Picklesオブジェクト
	 * @param object $options プラグイン設定
	 */
	static public function register( $px = null, $options = null ){
		if( count(func_get_args()) <= 1 ){
			return __CLASS__.'::'.__FUNCTION__.'('.( is_array($px) ? json_encode($px) : '' ).')';
		}

		(new self( $px, $options ))->kick();

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
	private function kick(){
		return;
	}
}
