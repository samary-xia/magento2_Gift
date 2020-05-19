<?php
/**
 * @author:     xiaxixiang
 * @email:      1635055310@qq.com
 * @date:       2020/4/8
 * @descript:
 */

namespace Samary\Gidt\Model\ResourceModel\Rules;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\VersionControl\Collection
{
	protected function _construct()
	{
		$this->_init(\Samary\Gift\Model\Rules::class, \Samary\Gift\Model\ResourceModel\Rules::class);
	}
}