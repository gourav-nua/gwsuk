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



namespace Mirasvit\Related\Block;

use Magento\Catalog\Block\Product\AbstractProduct;
use Magento\Catalog\Block\Product\Context;
use Mirasvit\Related\Api\Data\BlockInterface;
use Mirasvit\Related\Service\ProductFinderService;

class Block extends AbstractProduct
{
    private $productFinderService;

    public function __construct(
        ProductFinderService $productFinderService,
        Context $context,
        array $data = []
    ) {
        $this->productFinderService = $productFinderService;

        parent::__construct($context, $data);
    }

    /**
     * @return string
     */
    public function getTemplate()
    {
        return $this->getBlock()->getDisplayTemplate();
    }

    /**
     * @return BlockInterface
     */
    public function getBlock()
    {
        return $this->getData(BlockInterface::class);
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->getBlock()->getDisplayTitle();
    }

    /**
     * Theme compatibility
     * @return \Magento\Catalog\Model\ResourceModel\Product\Collection
     */
    public function getItemCollection()
    {
        return $this->getProducts();
    }

    /**
     * @return \Magento\Catalog\Model\ResourceModel\Product\Collection
     */
    public function getProducts()
    {
        $block = $this->getBlock();

        // $this->getData('products') - path from slider
        $products = $this->getData('products') ?: $this->productFinderService->getProducts($block);

        return $products;
    }

    /**
     * @return int
     */
    public function getItemLimit()
    {
        return intval($this->getData('item_limit'));
    }

    /**
     * @param string $text
     *
     * @return string
     */
    public function escapeHtmlForAttr($text)
    {
        if (method_exists($this, 'escapeHtmlAttr')) {
            $text = $this->escapeHtmlAttr($text);
        } else {
            $text = $this->escapeHtml($text);
        }

        return $text;
    }
}
