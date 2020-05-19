<?php
/**
 * @author:     xiaxixiang
 * @email:      1635055310@qq.com
 * @date:       2020/4/8
 * @descript:
 */

namespace Samary\Gift\Model;

class Rules extends \Magento\Framework\Model\AbstractModel {

	protected function _construct() {
		$this->_init( 'Samary\Gift\Model\ResourceModel\Rules' );
	}


	/**
	 * @param $rule_id
	 *
	 * @return $this
	 * @throws \Magento\Framework\Exception\LocalizedException
	 */
	public function loadRuleId( $rule_id ) {
		$this->_getResource()->loadRuleId( $this, $rule_id );
		$this->_afterLoad();

		return $this;
	}

	/**
	 * @param $rule_id
	 * @param $data
	 *
	 * @throws \Magento\Framework\Exception\LocalizedException
	 */
	public function addRules( $rule_id, $data ) {
		$model      = $this->loadRuleId( $rule_id );
		$data['id'] = $model->getId();
		$model->setData( $data );
		$model->save();

		return;
	}
}