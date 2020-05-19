<?php
/**
 * @author:     xiaxixiang
 * @email:      1635055310@qq.com
 * @date:       2020/4/8
 * @descript:
 */

namespace Kriyya\Gift\Plugin\Model\ResourceModel\Rule;

use Magento\SalesRule\Model\ResourceModel\Rule\Collection;

class CollectionPlugin {
	public function after_initSelect( Collection $subject, $result ) {
		$subject->getSelect()->joinLeft(
			['rule_gift' => $subject->getTable('salesrule_gift')],
			'main_table.rule_id = rule_gift.rule_id',
			['gift_pid','stop_qty_stack']
		);
		return $subject;
	}
}