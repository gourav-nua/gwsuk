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



namespace Mirasvit\Related\Block\Adminhtml;

use Magento\Backend\Block\Template\Context;
use Mirasvit\Core\Block\Adminhtml\AbstractMenu;

class Menu extends AbstractMenu
{
    public function __construct(
        Context $context
    ) {
        $this->visibleAt(['mst_related']);

        parent::__construct($context);
    }

    /**
     * {@inheritdoc}
     */
    protected function buildMenu()
    {
        $this->addItem([
            'resource' => 'Mirasvit_Related::related',
            'title'    => __('Product Blocks'),
            'url'      => $this->urlBuilder->getUrl('mst_related/block/index'),
        ])->addItem([
            'resource' => 'Mirasvit_Related::related',
            'title'    => __('Product Selection Rules'),
            'url'      => $this->urlBuilder->getUrl('mst_related/rule/index'),
        ]);

        return $this;
    }
}
