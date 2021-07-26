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

use Magento\Catalog\Model\Category;
use Magento\Catalog\Model\Product;
use Magento\Checkout\Model\Session as CheckoutSession;
use Magento\Customer\Model\Session as CustomerSession;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Registry;
use Magento\Sales\Model\ResourceModel\Order\CollectionFactory as OrderCollectionFactory;
use Mirasvit\Related\Service\AreaContext\RequestContext;
use Mirasvit\Related\Service\AreaContext\ProductFinder\CategoryService;
use Mirasvit\Related\Service\AreaContext\ProductFinder\CustomerService;
use Mirasvit\Related\Service\AreaContext\ProductFinder\OrderService;
use Mirasvit\Related\Service\AreaContext\ProductFinder\QuoteProductService;

class AreaContextService
{
    /** @var \Magento\Framework\App\Request\Http */
    private $request;

    private $areaContextState;

    private $categoryService;

    private $checkoutSession;

    private $customerService;

    private $customerSession;

    private $orderCollectionFactory;

    private $orderService;

    private $quoteProductService;

    private $registry;

    public function __construct(
        RequestInterface $request,
        CheckoutSession $checkoutSession,
        RequestContext $areaContextState,
        CustomerSession $customerSession,
        OrderCollectionFactory $orderCollectionFactory,
        CategoryService $categoryService,
        CustomerService $customerService,
        OrderService $orderService,
        QuoteProductService $quoteProductService,
        Registry $registry
    ) {
        $this->request                = $request;
        $this->areaContextState       = $areaContextState;
        $this->categoryService        = $categoryService;
        $this->checkoutSession        = $checkoutSession;
        $this->customerService        = $customerService;
        $this->customerSession        = $customerSession;
        $this->orderCollectionFactory = $orderCollectionFactory;
        $this->orderService           = $orderService;
        $this->quoteProductService    = $quoteProductService;
        $this->registry               = $registry;
    }

    public function getAttributeValue($attributeCode)
    {
        $products = $this->findProducts($this->areaContextState);
        $category = $this->findCategory();

        if (!$products) {
            return false;
        }

        if ($attributeCode == 'category_ids') {
            if ($category) {
                return [$category->getId()];
            }

            return $products[0]->getCategoryIds();
        }

        $values = [];
        foreach ($products as $product) {
            $values[] = $product->getData($attributeCode);
        }

        return $values;
    }

    /**
     * @param AreaContext\RequestContext $areaContext
     *
     * @return Product[]
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function findProducts($areaContext)
    {
        if ($areaContext->getProduct()) {
            return [$areaContext->getProduct()];
        }

        if ($areaContext->getIsCart()) {
            return $this->quoteProductService->find();
        }

        if ($areaContext->getCustomerId()) {
            $this->customerService->setCustomerId($areaContext->getCustomerId());

            return $this->customerService->find();
        }

        if ($areaContext->getOrderId()) {
            return $this->orderService->find();
        }

        if ($areaContext->getCategoryId()) {
            return $this->categoryService->find();
        }

        return [];
    }

    /**
     * @return Category|false
     */
    private function findCategory()
    {
        return $this->registry->registry('current_category');
    }
}
