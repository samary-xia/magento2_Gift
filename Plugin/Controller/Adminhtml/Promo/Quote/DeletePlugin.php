<?php
/**
 * @author:     xiaxixiang
 * @email:      1635055310@qq.com
 * @date:       2020/4/8
 * @descript:   重写购物车规则删除方法
 */

namespace Kriyya\Gift\Plugin\Controller\Adminhtml\Promo\Quote;

use Magento\SalesRule\Controller\Adminhtml\Promo\Quote\Delete;

class DeletePlugin {

	/**
	 * @var \Kriyya\Gift\Model\Rules
	 */
	protected $_giftRules;

	/**
	 * DeletePlugin constructor.
	 *
	 * @param \Kriyya\Gift\Model\Rules $giftRules
	 */
	public function __construct
	(
		\Kriyya\Gift\Model\Rules $giftRules
	) {
		$this->_giftRules = $giftRules;
	}

	/**
	 * @param Delete $subject
	 * @param $result
	 *
	 * @return mixed
	 * @throws \Magento\Framework\Exception\LocalizedException
	 * @description 在删除购物车规则时，将对应的礼品信息一同删除
	 */
	public function afterExecute( Delete $subject, $result ) {
		$id    = $subject->getRequest()->getParam( 'id' );
		$model = $this->_giftRules->loadRuleId( $id );
		$model->delete();

		return $result;

	}
}