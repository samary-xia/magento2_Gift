<?php
/**
 * @author:     xiaxixiang
 * @email:      1635055310@qq.com
 * @date:       2020/3/27
 * @descript:
 */

namespace Samary\Gift\Model\Quote;

class Info {
	/**
	 * @var array
	 */
	protected static $giftInfo = [];

	/**
	 * @param $giftInfo
	 */
	public function set( $giftInfo ) {
		self::$giftInfo = $giftInfo;
	}

	/**
	 * @return array
	 */
	public function get() {
		return self::$giftInfo;
	}
}