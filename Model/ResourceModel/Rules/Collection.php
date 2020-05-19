<?php
/**
 * @author:     xiaxixiang
 * @email:      1635055310@qq.com
 * @date:       2020/4/8
 * @descript:
 */

namespace Kriyya\Gidt\Model\ResourceModel\Rules;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\VersionControl\Collection
{
	protected function _construct()
	{
		$this->_init(\Kriyya\Gift\Model\Rules::class, \Kriyya\Gift\Model\ResourceModel\Rules::class);
	}
}