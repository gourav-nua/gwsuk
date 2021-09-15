<?php
 
namespace Nuadesign\CustomProduct\Observer;
 
use Magento\Framework\Event\Observer as EventObserver;
use Magento\Framework\Event\ObserverInterface;
use Magento\Checkout\Model\Cart as CustomerCart;
 
class RemoveGravityProduct implements ObserverInterface
{
    /**
     * @var RequestInterface
     */
    protected $_request;
 
    /**
    * Json Serializer
    *
    * @var JsonSerializer
    */
    protected $jsonSerializer;
     
    protected $cart;
    /**
     * 
     *
     * @param EventObserver $observer
     * @return $this
     */
    public function __construct(
        CustomerCart $cart
    ) {
        $this->cart = $cart;
    }
 
    /**
     * @param \Magento\Framework\Event\Observer $observer
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $quoteItem = $observer->getQuoteItem();
        $currentGiftProductQuoteItemId = $quoteItem->getGiftProductItemId();
        $this->cart->removeItem($currentGiftProductQuoteItemId)->save();
    }
}