<?php
/**
 * @author:     xiaxixiang
 * @email:      1635055310@qq.com
 * @date:       2020/3/27
 * @descript:
 */

namespace Kriyya\Gift\Model\Rule\Action\Discount;

use Magento\Framework\App\ObjectManager;
use Kriyya\Gift\Model\Quote\Info;
use Magento\Setup\Exception;

class ByGift extends \Magento\SalesRule\Model\Rule\Action\Discount\AbstractDiscount {

	/**
	 * @var \Kriyya\Gift\Model\Rules
	 */
	protected $_giftRules;

	/**
	 * @var Info
	 */
	protected $_info;

	public function __construct
	(
		\Magento\SalesRule\Model\Validator $validator,
		\Magento\SalesRule\Model\Rule\Action\Discount\DataFactory $discountDataFactory,
		\Magento\Framework\Pricing\PriceCurrencyInterface $priceCurrency,
		\Kriyya\Gift\Model\Rules $giftRules,
		\Kriyya\Gift\Model\Quote\Info $info
	) {
		$this->_giftRules = $giftRules;
		$this->_info      = $info;
		parent::__construct( $validator, $discountDataFactory, $priceCurrency );
	}

	/**
	 * @param \Magento\SalesRule\Model\Rule $rule
	 * @param \Magento\Quote\Model\Quote\Item\AbstractItem $item
	 * @param float $qty
	 *
	 * @return \Magento\SalesRule\Model\Rule\Action\Discount\Data
	 */
	public function calculate( $rule, $item, $qty ) {

		/** @var \Magento\SalesRule\Model\Rule\Action\Discount\Data $discountData */
		$discountData = $this->discountFactory->create();
		$quoteAmount  = $this->priceCurrency->convert( $rule->getDiscountAmount(), $item->getQuote()->getStore() );
		$discountData->setAmount( $qty * $quoteAmount );
		$discountData->setBaseAmount( $qty * $rule->getDiscountAmount() );
		//向购物车中添加礼品
		if ( $item->getPrice() > 0 ) {
			$giftRules = $this->_giftRules->loadRuleId( $rule->getId() );
			$this->saveInfo( $giftRules->getGiftPid(), $qty, $giftRules->getStopQtyStack() );
		}

		return $discountData;
	}

	/**
	 * @param $giftPid
	 * @param $qty
	 *
	 * @description 保存礼品信息
	 */
	protected function saveInfo( $giftPids, $qty, $stopStack = false ) {
		try {
			$data     = $this->_info->get();
			$giftPids = explode( ',', $giftPids );
			foreach ( $giftPids as $giftPid ) {
				if ( $stopStack && isset( $data[ $giftPid ] ) ) {
					continue;
				}
				if ( isset( $data[ $giftPid ] ) ) {
					$data[ $giftPid ] += $qty;
				} else {
					$data[ $giftPid ] = $qty;
				}
			}

			$this->_info->set( $data );
		} catch ( Exception $e ) {

		}

		return;
	}
}

