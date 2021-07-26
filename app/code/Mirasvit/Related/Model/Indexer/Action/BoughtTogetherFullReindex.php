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



namespace Mirasvit\Related\Model\Indexer\Action;

use Magento\Framework\App\ResourceConnection;
use Mirasvit\Related\Api\Data\IndexInterface;

class BoughtTogetherFullReindex
{
    private $resource;

    private $connection;

    public function __construct(
        ResourceConnection $resource
    ) {
        $this->resource   = $resource;
        $this->connection = $resource->getConnection();
    }

    public function reindexAll()
    {
        $this->clearIndex();

        $this->reindexSimple();
        $this->reindexLinked();
    }

    private function reindexSimple()
    {
        $keySql = new \Zend_Db_Expr('CONCAT(a.product_id, b.product_id)');

        $select = $this->connection->select();
        $select->from(
            ['a' => $this->resource->getTableName('sales_order_item')],
            ''
        )->joinInner(
            ['o' => $this->resource->getTableName('sales_order')],
            'a.order_id = o.entity_id',
            ''
        )->joinInner(
            ['b' => $this->resource->getTableName('sales_order_item')],
            'b.order_id = o.entity_id',
            ''
        )->where('a.product_id <> b.product_id')
            ->group($keySql)
            ->columns([
                IndexInterface::SOURCE            => new \Zend_Db_Expr($this->connection->quote(IndexInterface::SOURCE_BOUGHT_TOGETHER)),
                IndexInterface::STORE_ID          => 'o.store_id',
                IndexInterface::PRODUCT_ID        => 'a.product_id',
                IndexInterface::LINKED_PRODUCT_ID => 'b.product_id',
                IndexInterface::SCORE             => "COUNT({$keySql})",
            ]);


        $query = $this->connection->insertFromSelect(
            $select,
            $this->getIdxTable(),
            [
                IndexInterface::SOURCE,
                IndexInterface::STORE_ID,
                IndexInterface::PRODUCT_ID,
                IndexInterface::LINKED_PRODUCT_ID,
                IndexInterface::SCORE,
            ]
        );
        $this->connection->query($query);
    }

    private function reindexLinked()
    {
        $keySql = new \Zend_Db_Expr('CONCAT(l1.parent_id, l2.parent_id)');

        $select = $this->connection->select();
        $select->from(
            ['a' => $this->resource->getTableName('sales_order_item')],
            ''
        )->joinInner(
            ['o' => $this->resource->getTableName('sales_order')],
            'a.order_id = o.entity_id',
            ''
        )->joinInner(
            ['b' => $this->resource->getTableName('sales_order_item')],
            'b.order_id = o.entity_id',
            ''
        )->joinInner(
            ['l1' => $this->resource->getTableName('catalog_product_super_link')],
            'l1.product_id = a.product_id',
            ''
        )->joinInner(
            ['l2' => $this->resource->getTableName('catalog_product_super_link')],
            'l2.product_id = b.product_id',
            ''
        )->where('l1.parent_id <> l2.parent_id')
            ->where('l1.parent_id IS NOT NULL')
            ->where('l2.parent_id IS NOT NULL')
            ->group($keySql)
            ->columns([
                IndexInterface::SOURCE            => new \Zend_Db_Expr($this->connection->quote(IndexInterface::SOURCE_BOUGHT_TOGETHER)),
                IndexInterface::STORE_ID          => 'o.store_id',
                IndexInterface::PRODUCT_ID        => 'l1.parent_id',
                IndexInterface::LINKED_PRODUCT_ID => 'l2.parent_id',
                IndexInterface::SCORE             => "COUNT({$keySql})",
            ]);

        $query = $this->connection->insertFromSelect(
            $select,
            $this->getIdxTable(),
            [
                IndexInterface::SOURCE,
                IndexInterface::STORE_ID,
                IndexInterface::PRODUCT_ID,
                IndexInterface::LINKED_PRODUCT_ID,
                IndexInterface::SCORE,
            ]
        );
        $this->connection->query($query);
    }

    private function clearIndex()
    {
        $this->connection->delete(
            $this->getIdxTable(),
            $this->connection->quoteInto(IndexInterface::SOURCE . '=?', IndexInterface::SOURCE_BOUGHT_TOGETHER)
        );
    }

    private function getIdxTable()
    {
        return $this->resource->getTableName(IndexInterface::TABLE_NAME);
    }
}
