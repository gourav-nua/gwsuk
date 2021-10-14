<?php
namespace Nuadesign\CustomProduct\Block;

use Magento\Framework\View\Element\Template;

class GlasswarePage extends Template
{
    
    protected $productRepository;
    protected $categoryFactory;

    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Pricing\Helper\Data $priceHelper,
        \Magento\Catalog\Api\ProductRepositoryInterface $productRepository,
        \Magento\Catalog\Model\CategoryFactory $categoryFactory,
        array $data = []
    ) {
        $this->categoryFactory = $categoryFactory;
        $this->priceHelper = $priceHelper;
        $this->productRepository = $productRepository;
        parent::__construct($context, $data);
    }

    public function getCategoryDetails($categoryId)
    {
        return $this->categoryFactory->create()->load($categoryId);
        //return $category->getProductCollection()->addAttributeToSelect('*')->setPageSize(4);
    }

    public function getGlasswareCategory(){
        $category = $this->categoryFactory->create()->getCollection();
        $category->addAttributeToSelect('*')
        ->addAttributeToFilter('show_glassware_product',['eq'=>1]);
        return $category;
    }
}
