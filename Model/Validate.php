<?php
/**
 * @author:     xiaxixiang
 * @email:      1635055310@qq.com
 * @date:       2020/3/27
 * @descript:
 */


namespace Samary\Gift\Model;

use Magento\{Checkout\Model\Session, Setup\Exception};
use Samary\Gift\{
	Model\Gift
};

class Validate {

	/**
	 * @var Session
	 */
	protected $_quote;

	/**
	 * @var \Samary\Gift\Model\Gift
	 */
	protected $_gift;

	protected $_errorLog;

	/**
	 * Validate constructor.
	 *
	 * @param \Samary\Gift\Model\Gift $gift
	 * @param Session $quote
	 */
	public function __construct
	(
		Gift $gift,
		Session $quote,
		\Samary\CustomLog\Logger\Gift\ErrorLogger $errorLogger
	) {
		$this->_gift     = $gift;
		$this->_quote    = $quote;
		$this->_errorLog = $errorLogger;
	}

	/**
	 * @param $gifts 礼品信息变量,其中保存着当前购物车满足条件的礼品信息
	 *
	 * @return bool
	 */
	public function giftValidate( $gifts ) {
		try {
			$items = $this->_quote->getQuote()->getItemsCollection()->addFieldToFilter( 'price', [ 'lteq' => 0 ] );
			if ( count( $gifts ) <= 0 && count( $items ) <= 0 ) {
				return true;
			} else {
				try {
					if ( count( $items ) ) {
						foreach ( $items as $key => $item ) {
							if ( $item->getPrice() > 0 ) {
								continue;
							}

							if ( ! array_key_exists( $item->getProductId(), $gifts ) ) {
								//如果当前价格为0的item不在礼品信息变量$gifts中，删除当前item
								$item->delete();
								unset( $gifts[ $item->getProductId() ] );
								continue;
							}
							//礼品已经加入购物车的情况下重置礼品数量
							$item->setQty( $gifts[ $item->getProductId() ] );
							unset( $gifts[ $item->getProductId() ] );
						}
					}
				}catch (Exception $e){
					$this->_errorLog->error( $e->getMessage() );
				}

				try {
					if ( count( $gifts ) ) {
						//奖励品添加到购物车中
						$this->_gift->adds( $gifts );
					}
				} catch ( Exception $e ) {
					$this->_errorLog->error( $e->getMessage() );
				}
				if ( count( $this->_quote->getQuote()->getAllItems() ) <= 0 ) {
					//如果购物车为空，将quote中的itemsQty和itemsCount设置为0 --重要！！！
					$this->_quote->getQuote()->setItemsQty( 0 )->setItemsCount( 0 );
				}
			}
		} catch ( Exception $e ) {
			$this->_errorLog->error( $e->getMessage() );
		}

		return true;
	}
}
