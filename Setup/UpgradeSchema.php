<?php
/**
 * @author:     xiaxixiang
 * @email:      1635055310@qq.com
 * @date:       2020/3/31
 * @descript:
 */


namespace Kriyya\Gift\Setup;

use Magento\Framework\Setup\UpgradeSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;

class UpgradeSchema implements UpgradeSchemaInterface {
	public function upgrade( SchemaSetupInterface $setup, ModuleContextInterface $context ) {
		$setup->startSetup();

		if ( version_compare( $context->getVersion(), '1.1.0', '<' ) ) {
			$this->modifyGiftPid( $setup );
		}

		if ( version_compare( $context->getVersion(), '1.2.0', '<' ) ) {
			$this->delColumn( $setup );
			$this->addTable( $setup );
		}

		$setup->endSetup();
	}

	protected function modifyGiftPid( SchemaSetupInterface $setup ) {
		$setup->getConnection()->modifyColumn(
			$setup->getTable( 'salesrule' ),
			'gift_pid',
			[
				'type'     => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
				'length'   => '255',
				'nullable' => true,
				'default'  => '0',
				'comment'  => '礼品的产品ID'
			]
		);

	}

	protected function delColumn( SchemaSetupInterface $setup ) {
		$setup->getConnection()->dropColumn( $setup->getTable( 'salesrule' ), 'gift_pid' );
	}

	protected function addTable( SchemaSetupInterface $setup ) {
		$table = $setup->getConnection()->newTable(
			$setup->getTable( 'salesrule_gift' )
		)->addColumn(
			'id',
			\Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
			11,
			[
				'identity' => true,
				'unsigned' => true,
				'nullable' => false,
				'primary'  => true
			],
			'主键ID'
		)->addColumn(
			'rule_id',
			\Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
			11,
			[
				'nullable' => false
			],
			'Rule Id'
		)->addColumn(
			'gift_pid',
			\Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
			255,
			[
				'nullable' => true,
				'default'  => '0'
			],
			'礼品的产品ID'
		)->addColumn(
			'stop_qty_stack',
			\Magento\Framework\DB\Ddl\Table::TYPE_BOOLEAN,
			null,
			[
				'nullable' => true,
				'default'  => '0'
			],
			'禁止数量叠加'
		) ->addIndex(
			$setup->getIdxName('salesrule_gift', ['rule_id']),
			['rule_id']
		);
		$setup->getConnection()->createTable( $table );
	}
}