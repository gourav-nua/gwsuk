<?php
namespace Nuadesign\CustomProduct\Block;

use Magento\Framework\View\Element\Template;

class GravityGift extends Template
{
    
    protected $productRepository;

    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Pricing\Helper\Data $priceHelper,
        \Magento\Catalog\Api\ProductRepositoryInterface $productRepository,
        array $data = []
    ) {
        $this->priceHelper = $priceHelper;
        $this->productRepository = $productRepository;
        parent::__construct($context, $data);
    }

    public function getProductBySku($productsku)
    {
        return $this->productRepository->get($productsku);
    }

    public function getPriceFormatted($productObj)
    {
        return $this->priceHelper->currency($productObj->getFinalPrice(), true, false);
    }
}
