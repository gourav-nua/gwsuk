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


declare(strict_types=1);

namespace Mirasvit\Related\Plugin\Frontend;

use Mirasvit\Related\Api\Data\BlockInterface;
use Mirasvit\Related\Repository\BlockRepository;
use Mirasvit\Related\Service\ProductFinderService;

/**
 * @see \Magento\Catalog\Block\Product\ProductList\Related::getItems()
 * @see \Magento\Catalog\Block\Product\ProductList\Upsell::getItems()
 * @see \Magento\Checkout\Block\Cart\Crosssell::getItems()
 */
class InjectRelatedToNativeBlockPlugin
{
    protected $layoutPosition = 'inject_related';

    protected $blockRepository;

    protected $productFinderService;

    public function __construct(
        BlockRepository $blockRepository,
        ProductFinderService $productFinderService
    ) {
        $this->blockRepository      = $blockRepository;
        $this->productFinderService = $productFinderService;
    }

    public function afterGetItemCollection($object, $items)
    {
        $block = $this->getBlock();

        if (!$block) {
            return $items;
        }

        return $this->productFinderService->getProducts($block);
    }

    public function afterGetItems($object, $items)
    {
        $block = $this->getBlock();

        if (!$block) {
            return $items;
        }

        return $this->productFinderService->getProducts($block);
    }

    protected function getBlock(): ?BlockInterface
    {
        /** @var BlockInterface $block */
        $block = $this->blockRepository->getCollection()
            ->addFieldToFilter(BlockInterface::IS_ACTIVE, 1)
            ->addFieldToFilter(BlockInterface::LAYOUT_POSITION, $this->layoutPosition)
            ->getFirstItem();

        return $block->getId() ? $block : null;
    }
}
