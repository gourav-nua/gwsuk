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

class ViewedTogetherFullReindex
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
            ['a' => $this->resource->getTableName('report_viewed_product_index')],
            ''
        )->joinInner(
            ['b' => $this->resource->getTableName('report_viewed_product_index')],
            'a.customer_id = b.customer_id',
            ''
        )->where('a.product_id <> b.product_id')
            ->group($keySql)
            ->columns([
                IndexInterface::SOURCE            => new \Zend_Db_Expr($this->connection->quote(IndexInterface::SOURCE_VIEWED_TOGETHER)),
                IndexInterface::STORE_ID          => new \Zend_Db_Expr('0'),
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
            $this->connection->quoteInto(IndexInterface::SOURCE . '=?', IndexInterface::SOURCE_VIEWED_TOGETHER)
        );
    }

    private function getIdxTable()
    {
        return $this->resource->getTableName(IndexInterface::TABLE_NAME);
    }
}
