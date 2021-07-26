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



namespace Mirasvit\Related\Service;

use Mirasvit\Related\Api\Data\BlockInterface;
use Mirasvit\Related\Block\BlockWrapper;

class LayoutXmlGeneratorService
{
    private $positionInterpreter;

    public function __construct(
        LayoutPositionInterpreterService $positionInterpreter
    ) {
        $this->positionInterpreter = $positionInterpreter;
    }

    public function getXml(BlockInterface $block)
    {
        $name = 'related_block_' . $block->getId();

        $position = $this->positionInterpreter->decode($block->getLayoutPosition());

        $xml   = [];
        $xml[] = '<body>';
        $xml[] = sprintf('<referenceContainer name="%s">', $position['container']);
        $xml[] = sprintf('<block class="%s" name="%s">', BlockWrapper::class, $name);
        $xml[] = '<action method="setData">';
        $xml[] = sprintf('<argument name="name" xsi:type="string">%s</argument>', BlockInterface::ID);
        $xml[] = sprintf('<argument name="value" xsi:type="number">%s</argument>', $block->getId());
        $xml[] = '</action>';
        $xml[] = '</block>';
        $xml[] = '</referenceContainer>';

        if ($block->isLayoutRemoveRelated()) {
            $xml[] = sprintf('<referenceBlock name="%s" remove="true"/>', 'catalog.product.related');
        }
        if ($block->isLayoutRemoveUpSells()) {
            $xml[] = sprintf('<referenceBlock name="%s" remove="true"/>', 'product.info.upsell');
        }
        if ($block->isLayoutRemoveUpSells()) {
            $xml[] = sprintf('<referenceBlock name="%s" remove="true"/>', 'checkout.cart.crosssell');
        }

        $xml[] = '</body>';

        return implode('', $xml);
    }
}
