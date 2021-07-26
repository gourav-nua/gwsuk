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



namespace Mirasvit\Related\Service\AreaContext\ProductFinder;

use Magento\Catalog\Model\CategoryRepository;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory;
use Mirasvit\Related\Service\AreaContext\RequestContext;

class CategoryService
{
    private $requestContext;

    private $categoryRepository;

    private $productCollectionFactory;

    public function __construct(
        CategoryRepository $categoryRepository,
        CollectionFactory $productCollectionFactory,
        RequestContext $requestContext
    ) {
        $this->requestContext           = $requestContext;
        $this->categoryRepository       = $categoryRepository;
        $this->productCollectionFactory = $productCollectionFactory;
    }

    /**
     * @return array
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function find()
    {
        /** @var \Magento\Catalog\Model\ResourceModel\Product\Collection $collection */
        $collection = $this->productCollectionFactory->create();

        $category = $this->categoryRepository->get($this->requestContext->getCategoryId());

        $collection->addCategoryFilter($category);

        return $collection->getItems();
    }
}
