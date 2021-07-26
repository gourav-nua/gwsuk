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

class Slider extends AbstractProduct
{
    protected $_template = 'Mirasvit_Related::block-slider.phtml';

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
     * @return BlockInterface
     */
    public function getBlock()
    {
        return $this->getData(BlockInterface::class);
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

        $products = $this->productFinderService->getProducts($block);

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

    /**
     * @return int
     */
    public function getProductsPurPage()
    {
        return $this->getBlock()->getDisplayIsUseSlider() ?
            $this->getBlock()->getDisplayProductsPerPage() :
            $this->getBlock()->getDisplayProductsLimit();
    }

    /**
     * @return int
     */
    public function getPagesAmount()
    {
        return $this->getBlock()->getDisplayIsUseSlider() ?
            ceil($this->getBlock()->getDisplayProductsLimit() / $this->getProductsPurPage()) :
            1;
    }

    public function getJsonConfig()
    {
        return [
            '[data-element=productsList]' => [
                'Mirasvit_Related/js/related_slider' => [],
            ],
        ];
    }

    /**
     * @param \Magento\Catalog\Model\Product[] $products
     *
     * @return string
     */
    public function getInstanceHtml($products)
    {
        $block = $this->getBlock();

        $productCollection = $this->getProducts();
        $productCollection->removeAllItems();

        foreach ($products as $product) {
            $productCollection->addItem($product);
        }

        /** @var Block $instance */
        $instance = $this->_layout->createBlock(Block::class);

        $instance->setData(BlockInterface::class, $block);
        $instance->setData('products', $products);

        return trim($instance->toHtml());
    }
}
