<?php
/**
 * @author xiaxixiang
 * @email 1635055310@qq.com
 * @description
 */

namespace Kriyya\Gift\Setup;

use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;

/**
 * @codeCoverageIgnore
 */
class InstallSchema implements InstallSchemaInterface {
	/**
	 * {@inheritdoc}
	 */
	public function install( SchemaSetupInterface $setup, ModuleContextInterface $context ) {
		$installer = $setup;
		$installer->startSetup();

		$installer->getConnection()->addColumn($installer->getTable('salesrule'), 'gift_pid', [
			'type' => \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
			'length' => '11',
			'nullable' => true,
			'default' => '0',
			'comment' => '礼品的产品ID'
		]);

		$installer->endSetup();
	}
}
