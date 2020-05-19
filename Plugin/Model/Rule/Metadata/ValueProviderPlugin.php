<?php
/**
 * @author:     xiaxixiang
 * @email:      1635055310@qq.com
 * @date:       2020/3/26
 * @descript:   为后台购物车规则增加礼品规则
 */

namespace Samary\Gift\Plugin\Model\Rule\Metadata;

use Magento\SalesRule\Model\Rule\Metadata\ValueProvider;

class ValueProviderPlugin
{

	public function afterGetMetadataValues(ValueProvider $subject,$result)
	{
		$giftAction = ['label' => __('Add gifts to cart automatically'), 'value' =>  'by_gift'];
		$result['actions']['children']['simple_action']['arguments']['data']['config']['options'][] = $giftAction;
		return $result;
	}
}