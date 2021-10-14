<?php


namespace Nuadesign\CustomProduct\Setup;

use Magento\Eav\Setup\EavSetup;
use Magento\Eav\Setup\EavSetupFactory;
use Magento\Framework\Setup\UpgradeDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;

class UpgradeData implements UpgradeDataInterface
{
	private $eavSetupFactory;
	protected $logger;

	public function __construct(EavSetupFactory $eavSetupFactory,\Psr\Log\LoggerInterface $logger) {
	 $this->eavSetupFactory = $eavSetupFactory;
	 $this->logger = $logger;
	 }

	 public function upgrade(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
    	$eavSetup = $this->eavSetupFactory->create(['setup' => $setup]);
    	if (version_compare($context->getVersion(), '1.0.1') < 0) {
	        $eavSetup->addAttribute(
	            \Magento\Catalog\Model\Category::ENTITY,
	            'show_glassware_product',
	            [
	                'type' => 'int',
	                'label' => 'Show Glassware Product',
	                'input' => 'select',
	                'sort_order' => 333,
	                'source' => 'Magento\Eav\Model\Entity\Attribute\Source\Boolean',
	                'global' => 1,
	                'visible' => true,
	                'required' => false,
	                'user_defined' => false,
	                'default' => null,
	                'group' => 'General Information',
	                'backend' => ''
	            ]
	        );	
	    }
    }
}