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

use Magento\Catalog\Model\Config as CatalogConfig;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory as ProductCollectionFactory;
use Magento\CatalogInventory\Helper\Stock as StockHelper;
use Magento\Framework\App\ResourceConnection;
use Mirasvit\Related\Api\Data\BlockInterface;
use Mirasvit\Related\Api\Data\IndexInterface;
use Mirasvit\Related\Api\Data\RuleInterface;
use Mirasvit\Related\Repository\RuleRepository;

class ProductFinderService
{
    private $productCollectionFactory;

    private $areaContextService;

    private $ruleRepository;

    private $resource;

    private $catalogConfig;

    private $stockHelper;

    public function __construct(
        ProductCollectionFactory $productCollectionFactory,
        RuleRepository $ruleRepository,
        AreaContextService $areaContextService,
        ResourceConnection $resource,
        CatalogConfig $catalogConfig,
        StockHelper $stockHelper
    ) {
        $this->productCollectionFactory = $productCollectionFactory;
        $this->ruleRepository           = $ruleRepository;
        $this->areaContextService       = $areaContextService;
        $this->resource                 = $resource;
        $this->catalogConfig            = $catalogConfig;
        $this->stockHelper              = $stockHelper;
    }

    public function getProducts(BlockInterface $block)
    {
        $rule       = $this->ruleRepository->get($block->getRuleId());
        $productIds = $this->getRuleProducts($rule);

        if (count($productIds) == 0) {
            $productIds = [0];
        }
        $collection = $this->getBaseCollection();
        $collection->setPageSize($block->getDisplayProductsLimit());
        $collection->addFieldToFilter('entity_id', ['in' => $productIds]);
        $collection->getSelect()->order('FIELD(e.entity_id, ' . implode(',', $productIds) . ')');

        return $collection;
    }

    private function getRuleProducts(RuleInterface $rule)
    {
        $collection = $this->getBaseCollection();

        $productIds = $this->areaContextService->getAttributeValue('entity_id');

        if ($rule->getSource() != RuleInterface::SOURCE_ALL) {
            if (!$productIds || count($productIds) == 0) {
                return [];
            }

            $collection->getSelect()->joinLeft(
                ['index' => $this->resource->getTableName(IndexInterface::TABLE_NAME)],
                'index.linked_product_id = e.entity_id',
                ''
            )
                ->where('index.source=?', $rule->getSource())
                ->where('index.product_id IN(?)', $productIds)
                ->order('SUM(index.score) desc');
        } else {
            # when score isn't applicable, order by rand
            $collection->getSelect()
                ->order('rand()');
        }

        $collection->getSelect()
            ->group('e.entity_id')
            ->limit(10);

        if ($productIds && count($productIds) > 0) {
            $collection->getSelect()
                ->where('e.entity_id NOT IN(?)', $productIds);
        }

        $ids = $rule->getRule()->getMatchedProductIds($collection);

        if ($productIds) {
            $nativeIds = $this->getNativeLinkedProductIds($productIds, $rule);

            $ids = array_merge($nativeIds, $ids);
        }

        return $ids;
    }

    private function getBaseCollection()
    {
        $collection = $this->productCollectionFactory->create();

        $collection
            ->addMinimalPrice()
            ->addFinalPrice()
            ->addTaxPercents()
            ->addUrlRewrite();

        $collection->addAttributeToSelect($this->catalogConfig->getProductAttributes());

        $collection->setVisibility([2, 4]);

        $this->stockHelper->addInStockFilterToCollection($collection);

        return $collection;
    }

    private function getNativeLinkedProductIds(array $productIds, RuleInterface $rule)
    {
        if (!$productIds) {
            return [];
        }

        // linkCodes = ['relation', 'up_sell', 'cross_sell'], see catalog_product_link_type table
        $linkCodes = [];
        if ($rule->getIsIncludeRelated()) {
            $linkCodes[] = 'relation';
        }
        if ($rule->getIsIncludeUpSells()) {
            $linkCodes[] = 'up_sell';
        }
        if ($rule->getIsIncludeCrossSells()) {
            $linkCodes[] = 'cross_sell';
        }

        if (!$linkCodes) {
            return [];
        }

        $connection = $this->resource->getConnection();
        $select     = $connection->select()->from(
            ['link' => $this->resource->getTableName('catalog_product_link')],
            ['linked_product_id']
        )->joinInner(
            ['type' => $this->resource->getTableName('catalog_product_link_type')],
            'link.link_type_id = type.link_type_id',
            []
        )->where('link.product_id IN(?)', $productIds
        )->where('type.code IN(?)', $linkCodes);

        return $connection->fetchCol($select);
    }
}
