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



namespace Mirasvit\Related\Service\AreaContext;

use Magento\Catalog\Model\Product;
use Magento\Customer\Model\Session as CustomerSession;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Registry;

/**
 * @method int getCategoryId()
 * @method $this setCategoryId(int $flag)
 * @method int getOrderId()
 * @method $this setOrderId(int $flag)
 */
class RequestContext extends \Magento\Framework\DataObject
{
    private $customerSession;

    private $registry;

    private $request;

    public function __construct(
        CustomerSession $customerSession,
        Registry $registry,
        RequestInterface $request
    ) {
        $this->request         = $request;
        $this->customerSession = $customerSession;
        $this->registry        = $registry;
    }

    /**
     * @return bool
     */
    public function getIsCart()
    {
        if (!$this->hasData('is_cart') &&
            $this->request->getFullActionName() === 'checkout_cart_index'
        ) {
            $this->setData('is_cart', true);
        }

        return $this->getData('is_cart');
    }

    /**
     * @param bool $isCart
     *
     * @return $this
     */
    public function setIsCart($isCart)
    {
        $this->setData('is_cart', $isCart);

        return $this;
    }

    /**
     * @return int
     */
    public function getCustomerId()
    {
        if (!$this->hasData('is_cart') &&
            $this->customerSession->getCustomerId()
        ) {
            $this->setData('customer_id', $this->customerSession->getCustomerId());
        }

        return $this->getData('customer_id');
    }

    /**
     * @param int $customerId
     *
     * @return $this
     */
    public function setCustomerId($customerId)
    {
        $this->setData('customer_id', $customerId);

        return $this;
    }

    /**
     * @return null|Product
     */
    public function getProduct()
    {
        if (!$this->hasData('product') &&
            $this->registry->registry('current_product')
        ) {
            $product = $this->registry->registry('current_product');

            $this->setData('product', $product);
        }

        return $this->getData('product');
    }

    /**
     * @param Product $product
     *
     * @return $this
     */
    public function setProduct($product)
    {
        $this->setData('product', $product);

        return $this;
    }
}
