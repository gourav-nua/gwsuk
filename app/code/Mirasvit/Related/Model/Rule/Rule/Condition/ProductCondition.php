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



namespace Mirasvit\Related\Model\Rule\Rule\Condition;

use Magento\Catalog\Model\ResourceModel\Product\Collection;
use Magento\CatalogRule\Model\Rule\Condition\Product as RuleConditionProduct;
use Magento\Rule\Block\Editable as EditableBlock;
use Mirasvit\Related\Service\AreaContextService;

/**
 * @method string getKind()
 * @method $this setKind($value)
 * @method string getPrefix()
 * @method string getId()
 */
class ProductCondition extends RuleConditionProduct
{
    private $areaContextService;

    private $queryBuilder;

    public function __construct(
        AreaContextService $areaContextService,
        QueryBuilder $queryBuilder,
        \Magento\Rule\Model\Condition\Context $context,
        \Magento\Backend\Helper\Data $backendData,
        \Magento\Eav\Model\Config $config,
        \Magento\Catalog\Model\ProductFactory $productFactory,
        \Magento\Catalog\Api\ProductRepositoryInterface $productRepository,
        \Magento\Catalog\Model\ResourceModel\Product $productResource,
        \Magento\Eav\Model\ResourceModel\Entity\Attribute\Set\Collection $attrSetCollection,
        \Magento\Framework\Locale\FormatInterface $localeFormat
    ) {
        $this->areaContextService = $areaContextService;
        $this->queryBuilder       = $queryBuilder;

        parent::__construct($context, $backendData, $config, $productFactory, $productRepository, $productResource, $attrSetCollection, $localeFormat);
    }

    public function getDefaultOperatorInputByType()
    {
        return [
            'string'      => ['==', '!=', '>=', '>', '<=', '<', '{}', '!{}', '()', '!()'],
            'numeric'     => ['==', '!=', '>=', '>', '<=', '<', '()', '!()'],
            'date'        => ['==', '>=', '<='],
            'select'      => ['==', '!=', '<=>'],
            'boolean'     => ['==', '!=', '<=>'],
            'multiselect' => ['()', '!()'],
            'category'    => ['()', '!()'],
            'grid'        => ['()', '!()'],
        ];
    }

    public function getValueSelectOptions()
    {
        $options = parent::getValueSelectOptions();
        if (is_array($options)) {
            $options = array_merge([
                [
                    'value' => '+',
                    'label' => __('Current Product "%1"', $this->getAttributeName()),
                ],
            ], $options);
        }

        return $options;
    }

    public function getOperatorElementHtml()
    {

        $elementId   = sprintf('%s__%s__kind', $this->getPrefix(), $this->getId());
        $elementName = sprintf($this->elementName . '[%s][%s][kind]', $this->getPrefix(), $this->getId());

        $options   = [
            [
                'value' => 'value',
                'label' => __('Exact value'),
            ],
            [
                'value' => 'product',
                'label' => __('Current Product "%1"', $this->getAttributeName()),
            ],
        ];
        $valueName = $options[0]['label'];
        foreach ($options as $option) {
            if ($option['value'] == $this->getKind()) {
                $valueName = $option['label'];
            }
        }

        $element = $this->getForm()->addField(
            $elementId,
            'select',
            [
                'name'           => $elementName,
                'values'         => $options,
                'value'          => $this->getKind(),
                'value_name'     => $valueName,
                'data-form-part' => $this->getFormName(),
            ]
        );
        /** @var EditableBlock $editable */
        $editable = $this->_layout->getBlockSingleton(EditableBlock::class);
        $element->setRenderer($editable);

        $script = '
            <script>
                 require(["jquery"], (function($) {
                     var $el = $("#' . $elementId . '");
                     setInterval(function() {
                        update();
                     }, 10);
                     
                     $el.on("change", function(e) {
                         update();
                     });
                     
                     function update() {
                         var $val = $($(".rule-param", $el.closest("li"))[2]);
                         
                         $el.val() === "product" ? $val.hide() : $val.show();
                     }
                 }));
            </script>
            ';

        return parent::getOperatorElementHtml() . $element->toHtml() . $script;
    }

    public function getSqlCondition(Collection $collection)
    {
        $select = $collection->getSelect();

        $field = $this->queryBuilder->joinAttribute($select, $this->getAttributeObject());

        $condition = $this->queryBuilder->buildCondition($field, $this->getOperator(), $this->getValueParsed());

        return $condition;
    }

    public function getValueParsed()
    {
        $value = parent::getValueParsed();

        // for multiselect fields value is array
        if ($this->isArrayOperatorType() && is_array($value) && $value[0] == '+') {

            $value = $this->areaContextService->getAttributeValue($this->getAttribute());

            if ($value) {
                $value = implode(',', $value);
                $this->unsetData('value_parsed');
                $this->setData('value', $value);

                $value = parent::getValueParsed();
            } else { // because false or null return all products
                $value = '+';
            }
        } elseif ($this->getKind() == 'product') {
            $value = $this->areaContextService->getAttributeValue($this->getAttribute());
        }

        return $value;
    }

    public function loadArray($arr)
    {
        $result = parent::loadArray($arr);
        $this->setKind(isset($arr['kind']) ? $arr['kind'] : false);

        return $result;
    }

    public function asArray(array $arrAttributes = [])
    {
        $result = parent::asArray($arrAttributes);

        $result['kind'] = $this->getKind();

        return $result;
    }
}