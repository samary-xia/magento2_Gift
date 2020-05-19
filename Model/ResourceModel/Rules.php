<?php
/**
 * @author:     xiaxixiang
 * @email:      1635055310@qq.com
 * @date:       2020/4/8
 * @descript:
 */

namespace Samary\Gift\Model\ResourceModel;


class Rules extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{

	protected function _construct()
	{
		$this->_init('salesrule_gift', 'id');
	}

	/**
	 * @param $salesrule
	 * @param $rule_id
	 *
	 * @return $this
	 */
	public function loadRuleId($giftRule, $rule_id)
	{
		$connection = $this->getConnection();
		$select = $this->_getLoadSelect(
			'rule_id',
			$rule_id,
			$giftRule
		)->limit(
			1
		);

		$data = $connection->fetchRow($select);

		if ($data) {
			$giftRule->setData($data);
		}

		$this->_afterLoad($giftRule);

		return $this;
	}

}