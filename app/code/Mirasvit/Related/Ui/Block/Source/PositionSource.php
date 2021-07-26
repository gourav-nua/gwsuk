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



namespace Mirasvit\Related\Ui\Block\Source;

use Magento\Framework\Data\OptionSourceInterface;
use Mirasvit\Related\Service\LayoutPositionInterpreterService;

class PositionSource implements OptionSourceInterface
{
    private $positionInterpreter;

    public function __construct(
        LayoutPositionInterpreterService $positionInterpreter
    ) {
        $this->positionInterpreter = $positionInterpreter;
    }

    public function toOptionArray()
    {
        $options = [
            [
                'label' => __('Product Page'),
                'value' => [
                    [
                        'label' => 'Product Page | Add into Native Related Block',
                        'value' => 'inject_related',
                    ],
                    [
                        'label' => 'Product Page | Add into Native Upsell Block',
                        'value' => 'inject_upsell',
                    ],
                    [
                        'label' => 'Product Page | Content Top',
                        'value' => $this->positionInterpreter->encode('catalog_product_view', 'content.top'),
                    ],
                    [
                        'label' => 'Product Page | Content Bottom',
                        'value' => $this->positionInterpreter->encode('catalog_product_view', 'content.bottom'),
                    ],
                    [
                        'label' => 'Product Page | Sidebar Top',
                        'value' => $this->positionInterpreter->encode('catalog_product_view', 'sidebar.main', '-'),
                    ],
                    [
                        'label' => 'Product Page | Sidebar Bottom',
                        'value' => $this->positionInterpreter->encode('catalog_product_view', 'sidebar.additional', '', '-'),
                    ],
                ],
            ],
            [
                'label' => __('Category Page'),
                'value' => [
                    [
                        'label' => 'Category Page | Content Top',
                        'value' => $this->positionInterpreter->encode('catalog_category_view', 'content.top'),
                    ],
                    [
                        'label' => 'Category Page | Content Bottom',
                        'value' => $this->positionInterpreter->encode('catalog_category_view', 'content.bottom'),
                    ],
                    [
                        'label' => 'Category Page | Sidebar Top',
                        'value' => $this->positionInterpreter->encode('catalog_category_view', 'content.main'),
                    ],
                    [
                        'label' => 'Category Page | Sidebar Bottom',
                        'value' => $this->positionInterpreter->encode('catalog_category_view', 'sidebar.additional'),
                    ],
                ],
            ],
            [
                'label' => __('Shopping Cart Page'),
                'value' => [
                    [
                        'label' => 'Shopping Cart Page | Add into Native Cross Sell Block',
                        'value' => 'inject_crosssell',
                    ],
                    [
                        'label' => 'Shopping Cart Page | Content Top',
                        'value' => $this->positionInterpreter->encode('checkout_cart_index', 'content.top'),
                    ],
                    [
                        'label' => 'Shopping Cart Page | Content Bottom',
                        'value' => $this->positionInterpreter->encode('checkout_cart_index', 'content.bottom'),
                    ],
                ],
            ],
            [
                'label' => __('Customer Account Pages'),
                'value' => [
                    [
                        'label' => 'Customer Account Pages | Content Top',
                        'value' => $this->positionInterpreter->encode('customer_account', 'content.top'),
                    ],
                    [
                        'label' => 'Customer Account Pages | Content Bottom',
                        'value' => $this->positionInterpreter->encode('customer_account', 'content.bottom'),
                    ],
                ],
            ],
            [
                'label' => __('Place block manually'),
                'value' => 'custom',
            ],
        ];

        return $options;
    }

    /**
     * @param string $value
     *
     * @return bool
     */
    public function isValueDefined($value)
    {
        foreach ($this->toOptionArray() as $set) {
            if ($set['value'] === $value) {
                return true;
            }

            if (is_array($set['value'])) {
                foreach ($set['value'] as $subSet) {
                    if ($subSet['value'] === $value) {
                        return true;
                    }
                }
            }
        }

        return false;
    }
}
