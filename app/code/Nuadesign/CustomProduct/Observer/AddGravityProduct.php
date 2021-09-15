<?php
namespace Nuadesign\CustomProduct\Observer;

use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Checkout\Model\SessionFactory as CheckoutSession;

class AddGravityProduct implements ObserverInterface
{
    protected $_productRepository;
    protected $_cart;

    public function __construct( 
        CheckoutSession $checkoutSession, 
        \Magento\Catalog\Model\ProductRepository $productRepository, 
        \Magento\Framework\App\RequestInterface $request, 
        \Magento\Checkout\Model\Cart $cart,
        \Magento\Quote\Api\CartRepositoryInterface $cartRepository
         ){
        $this->_productRepository = $productRepository;
        $this->_request = $request;
        $this->_cart = $cart;
        $this->_checkoutSession = $checkoutSession;
        $this->cartRepository = $cartRepository;
    }
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $param = $this->_request->getParam('giftproduct');
        $product = $observer->getEvent()->getData('product');
        $itemObject = $observer->getEvent()->getData('quote_item');
        if(isset($param) && $product->getId() != $param ){ 
            $params = new \Magento\Framework\DataObject([
                'product' => $param,
                'qty' => $product->getQty()
            ]);
            $_product = $this->_productRepository->getById($param);
            $session = $this->_checkoutSession->create();
            $quote = $session->getQuote();
            $giftQuoteItem = $quote->addProduct($_product, $params);
            $this->cartRepository->save($quote);
            $itemObject->setGiftProductItemId($giftQuoteItem->getItemId());
        }
    }
}