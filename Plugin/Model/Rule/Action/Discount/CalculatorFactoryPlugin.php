<?php
/**
 * @author:     xiaxixiang
 * @email:      1635055310@qq.com
 * @date:       2020/3/27
 * @descript:   增加礼品类
 */

namespace Samary\Gift\Plugin\Model\Rule\Action\Discount;

use Magento\SalesRule\Model\Rule\Action\Discount\CalculatorFactory;

class CalculatorFactoryPlugin extends CalculatorFactory
{
	/**
	 * @var array
	 */
	protected $customClassByType = [
		'by_gift' => \Samary\Gift\Model\Rule\Action\Discount\ByGift::class
	];

	public function beforeCreate( CalculatorFactory $subject ) {
		$subject->classByType = array_merge( $subject->classByType, $this->customClassByType );
		return;
	}
}