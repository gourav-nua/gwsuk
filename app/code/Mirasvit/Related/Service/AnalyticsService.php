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



namespace Mirasvit\Related\Service;

use Magento\Framework\App\ResourceConnection;
use Mirasvit\Related\Api\Data\AnalyticsInterface;

class AnalyticsService
{
    private $resource;

    public function __construct(
        ResourceConnection $resource
    ) {
        $this->resource = $resource;
    }

    public function getImpression($blockId)
    {
        return $this->getValue($blockId, AnalyticsInterface::ACTION_IMPRESSION);
    }

    public function getClicks($blockId)
    {
        return $this->getValue($blockId, AnalyticsInterface::ACTION_CLICK);
    }

    public function getOrders($blockId)
    {
        return $this->getValue($blockId, AnalyticsInterface::ACTION_ORDER);
    }

    public function getRevenue($blockId)
    {
        return $this->getValue($blockId, AnalyticsInterface::ACTION_REVENUE);
    }

    private function getValue($blockId, $action)
    {
        $select = $this->resource->getConnection()->select();
        $select->from(
            $this->resource->getTableName(AnalyticsInterface::TABLE_NAME),
            [new \Zend_Db_Expr('SUM(' . AnalyticsInterface::VALUE . ')')]
        )
            ->where(AnalyticsInterface::BLOCK_ID . ' = ?', $blockId)
            ->where(AnalyticsInterface::ACTION . ' = ?', $action);

        return $this->resource->getConnection()->fetchOne($select);
    }
}