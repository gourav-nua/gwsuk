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

use Magento\Checkout\Model\Session as CheckoutSession;

class QuoteProductService
{
    private $checkoutSession;

    public function __construct(
        CheckoutSession $checkoutSession
    ) {
        $this->checkoutSession = $checkoutSession;
    }

    /**
     * @return array
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function find()
    {
        $quote = $this->checkoutSession->getQuote();

        if (!$quote->getId()) {
            return [];
        }

        $products = [];
        foreach ($quote->getAllVisibleItems() as $item) {
            $products[] = $item->getProduct();
        }

        return $products;
    }
}
