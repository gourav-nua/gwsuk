<?php
namespace Nuadesign\CustomProduct\Block;

class RecentProducts extends \Magento\Catalog\Block\Product\AbstractProduct implements \Magento\Widget\Block\BlockInterface
{
    
    protected $_productCollectionFactory;
    protected $_viewProductsBlock;

    /**
     * RecentProducts constructor.
     * @param \Magento\Catalog\Block\Product\Context $context
     * @param \Magento\Catalog\Model\Product\Visibility $catalogProductVisibility
     * @param \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productsCollectionFactory
     * @param \Magento\Reports\Block\Product\Widget\Viewed $viewedProductsBlock
     * @param array $data
     */
    public function __construct(
        \Magento\Catalog\Block\Product\Context $context,
        \Magento\Catalog\Model\Product\Visibility $catalogProductVisibility,
        \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productsCollectionFactory,
        \Magento\Reports\Block\Product\Viewed $viewedProductsBlock,
        array $data = []
    ) {
        
        $this->_productCollectionFactory  = $productsCollectionFactory;
        $this->_viewProductsBlock         = $viewedProductsBlock;

        parent::__construct($context, $data);
    }

    /**
     * Retrieve the product collection based on product type.
     *
     * @return array|\Magento\Catalog\Model\ResourceModel\Product\Collection
     */
    public function getProductCollection()
    {
        $productCollection =  $this->_getRecentlyViewedCollection($this->_productCollectionFactory->create());
        return $productCollection;
    }

    
    protected function _getRecentlyViewedCollection($_collection)
    {
        $_collection = $this->_viewProductsBlock->getItemsCollection();
        $_collection->setPageSize(6);
        return $_collection;
    }
}
