<?php
/**
 * Mirasvit
 *
 * This source file is subject to the Mirasvit Software License, which is available at https://mirasvit.com/license/.
 * Do not edit or add to this file if you wish to upgrade the to newer versions in the future.
 * If you wish to customize this module for your needs.
 * Please refer to http://www.magentocommerce.com for more information.
 *
 * @category  Mirasvit
 * @package   mirasvit/module-related
 * @version   1.0.17
 * @copyright Copyright (C) 2021 Mirasvit (https://mirasvit.com/)
 */



namespace Mirasvit\Related\Setup;

use Magento\Framework\DB\Ddl\Table;
use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Mirasvit\Related\Api\Data\AnalyticsInterface;
use Mirasvit\Related\Api\Data\BlockInterface;
use Mirasvit\Related\Api\Data\IndexInterface;
use Mirasvit\Related\Api\Data\RuleInterface;

class InstallSchema implements InstallSchemaInterface
{
    /**
     * {@inheritdoc}
     */
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $installer = $setup;

        $connection = $installer->getConnection();

        $installer->startSetup();

        $table = $connection->newTable(
            $installer->getTable(BlockInterface::TABLE_NAME)
        )->addColumn(
            BlockInterface::ID,
            Table::TYPE_INTEGER,
            null,
            ['unsigned' => false, 'nullable' => false, 'identity' => true, 'primary' => true],
            BlockInterface::ID
        )->addColumn(
            BlockInterface::NAME,
            Table::TYPE_TEXT,
            255,
            ['nullable' => false],
            BlockInterface::NAME
        )->addColumn(
            BlockInterface::PRIORITY,
            Table::TYPE_INTEGER,
            null,
            ['nullable' => false, 'default' => 0],
            BlockInterface::PRIORITY
        )->addColumn(
            BlockInterface::IS_ACTIVE,
            Table::TYPE_INTEGER,
            1,
            ['nullable' => false, 'default' => 0],
            BlockInterface::IS_ACTIVE
        )->addColumn(
            BlockInterface::STORE_IDS,
            Table::TYPE_TEXT,
            255,
            ['nullable' => true],
            BlockInterface::STORE_IDS
        )->addColumn(
            BlockInterface::LAYOUT_UPDATE_ID,
            Table::TYPE_INTEGER,
            null,
            ['nullable' => true],
            BlockInterface::LAYOUT_UPDATE_ID
        )->addColumn(
            BlockInterface::LAYOUT_POSITION,
            Table::TYPE_TEXT,
            255,
            ['nullable' => true],
            BlockInterface::LAYOUT_POSITION
        )->addColumn(
            BlockInterface::LAYOUT_CONDITIONS,
            Table::TYPE_TEXT,
            '64k',
            ['nullable' => true],
            BlockInterface::LAYOUT_CONDITIONS
        )->addColumn(
            BlockInterface::LAYOUT_REMOVE_RELATED,
            Table::TYPE_INTEGER,
            1,
            ['nullable' => false, 'default' => 0],
            BlockInterface::LAYOUT_REMOVE_RELATED
        )->addColumn(
            BlockInterface::LAYOUT_REMOVE_CROSS_SELLS,
            Table::TYPE_INTEGER,
            1,
            ['nullable' => false, 'default' => 0],
            BlockInterface::LAYOUT_REMOVE_CROSS_SELLS
        )->addColumn(
            BlockInterface::LAYOUT_REMOVE_UP_SELLS,
            Table::TYPE_INTEGER,
            1,
            ['nullable' => false, 'default' => 0],
            BlockInterface::LAYOUT_REMOVE_UP_SELLS
        )->addColumn(
            BlockInterface::DISPLAY_TITLE,
            Table::TYPE_TEXT,
            255,
            ['nullable' => true],
            BlockInterface::DISPLAY_TITLE
        )->addColumn(
            BlockInterface::DISPLAY_PRODUCTS_LIMIT,
            Table::TYPE_INTEGER,
            null,
            ['nullable' => false, 'default' => 5],
            BlockInterface::DISPLAY_PRODUCTS_LIMIT
        )->addColumn(
            BlockInterface::DISPLAY_MODE,
            Table::TYPE_TEXT,
            255,
            ['nullable' => true],
            BlockInterface::DISPLAY_MODE
        )->addColumn(
            BlockInterface::RULE_ID,
            Table::TYPE_INTEGER,
            null,
            ['nullable' => true],
            BlockInterface::RULE_ID
        );

        $connection->dropTable($setup->getTable(BlockInterface::TABLE_NAME));
        $connection->createTable($table);

        $table = $connection->newTable(
            $installer->getTable(RuleInterface::TABLE_NAME)
        )->addColumn(
            RuleInterface::ID,
            Table::TYPE_INTEGER,
            null,
            ['unsigned' => false, 'nullable' => false, 'identity' => true, 'primary' => true],
            RuleInterface::ID
        )->addColumn(
            RuleInterface::NAME,
            Table::TYPE_TEXT,
            255,
            ['nullable' => false],
            RuleInterface::NAME
        )->addColumn(
            RuleInterface::SOURCE,
            Table::TYPE_TEXT,
            255,
            ['nullable' => false],
            RuleInterface::SOURCE
        )->addColumn(
            RuleInterface::CONDITIONS_SERIALIZED,
            Table::TYPE_TEXT,
            '64k',
            ['nullable' => true],
            RuleInterface::CONDITIONS_SERIALIZED
        );

        $connection->dropTable($setup->getTable(RuleInterface::TABLE_NAME));
        $connection->createTable($table);

        $table = $connection->newTable(
            $installer->getTable(IndexInterface::TABLE_NAME)
        )->addColumn(
            IndexInterface::ID,
            Table::TYPE_INTEGER,
            null,
            ['unsigned' => false, 'nullable' => false, 'identity' => true, 'primary' => true],
            IndexInterface::ID
        )->addColumn(
            IndexInterface::SOURCE,
            Table::TYPE_TEXT,
            255,
            ['nullable' => false],
            IndexInterface::SOURCE
        )->addColumn(
            IndexInterface::STORE_ID,
            Table::TYPE_INTEGER,
            null,
            ['nullable' => false],
            IndexInterface::STORE_ID
        )->addColumn(
            IndexInterface::PRODUCT_ID,
            Table::TYPE_INTEGER,
            null,
            ['nullable' => false],
            IndexInterface::PRODUCT_ID
        )->addColumn(
            IndexInterface::LINKED_PRODUCT_ID,
            Table::TYPE_INTEGER,
            null,
            ['nullable' => false],
            IndexInterface::LINKED_PRODUCT_ID
        )->addColumn(
            IndexInterface::SCORE,
            Table::TYPE_INTEGER,
            null,
            ['nullable' => false, 'default' => 0],
            IndexInterface::SCORE
        );

        $connection->dropTable($setup->getTable(IndexInterface::TABLE_NAME));
        $connection->createTable($table);

        $table = $connection->newTable(
            $installer->getTable(AnalyticsInterface::TABLE_NAME)
        )->addColumn(
            AnalyticsInterface::ID,
            Table::TYPE_INTEGER,
            null,
            ['unsigned' => false, 'nullable' => false, 'identity' => true, 'primary' => true],
            AnalyticsInterface::ID
        )->addColumn(
            AnalyticsInterface::BLOCK_ID,
            Table::TYPE_INTEGER,
            null,
            ['nullable' => false],
            AnalyticsInterface::BLOCK_ID
        )->addColumn(
            AnalyticsInterface::ACTION,
            Table::TYPE_TEXT,
            255,
            ['nullable' => false],
            AnalyticsInterface::ACTION
        )->addColumn(
            AnalyticsInterface::VALUE,
            Table::TYPE_DECIMAL,
            '12,1',
            ['nullable' => false],
            AnalyticsInterface::VALUE
        )->addColumn(
            AnalyticsInterface::SESSION_ID,
            Table::TYPE_TEXT,
            255,
            ['nullable' => false],
            AnalyticsInterface::SESSION_ID
        )->addColumn(
            AnalyticsInterface::CREATED_AT,
            Table::TYPE_TIMESTAMP,
            null,
            ['nullable' => false, 'default' => Table::TIMESTAMP_INIT],
            AnalyticsInterface::CREATED_AT
        );

        $connection->dropTable($setup->getTable(AnalyticsInterface::TABLE_NAME));
        $connection->createTable($table);
    }
}
