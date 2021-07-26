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

class AlsoBoughtFullReindex
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
            ['c' => $this->resource->getTableName('customer_entity')],
            'c.entity_id = o.customer_id',
            ''
        )->joinInner(
            ['o1' => $this->resource->getTableName('sales_order')],
            'c.entity_id = o1.customer_id',
            ''
        )->joinInner(
            ['b' => $this->resource->getTableName('sales_order_item')],
            'b.order_id = o1.entity_id',
            ''
        )->where('a.product_id <> b.product_id')
            ->group($keySql)
            ->columns([
                IndexInterface::SOURCE            => new \Zend_Db_Expr($this->connection->quote(IndexInterface::SOURCE_ALSO_BOUGHT)),
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

    private function clearIndex()
    {
        $this->connection->delete(
            $this->getIdxTable(),
            $this->connection->quoteInto(IndexInterface::SOURCE . '=?', IndexInterface::SOURCE_ALSO_BOUGHT)
        );
    }

    private function getIdxTable()
    {
        return $this->resource->getTableName(IndexInterface::TABLE_NAME);
    }
}