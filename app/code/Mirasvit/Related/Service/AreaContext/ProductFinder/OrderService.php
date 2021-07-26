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

use Magento\Sales\Model\ResourceModel\Order\CollectionFactory;
use Mirasvit\Related\Service\AreaContext\RequestContext;

class OrderService
{
    private $requestContext;

    private $orderCollectionFactory;

    public function __construct(
        CollectionFactory $orderCollectionFactory,
        RequestContext $requestContext
    ) {
        $this->requestContext         = $requestContext;
        $this->orderCollectionFactory = $orderCollectionFactory;
    }

    /**
     * @return array
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function find()
    {
        $collection = $this->orderCollectionFactory->create();
        $collection->addFieldToFilter('order_id', (int)$this->requestContext->getOrderId());

        $products = [];
        /** @var \Magento\Sales\Model\Order $order */
        foreach ($collection as $order) {
            /** @var \Magento\Sales\Model\Order\Item $item */
            foreach ($order->getAllVisibleItems() as $item) {
                $product = $item->getProduct();
                if ($product) {
                    $products[] = $product;
                }
            }
        }

        return $products;
    }
}
