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



namespace Mirasvit\Related\Model;

use Magento\Framework\Model\AbstractModel;
use Mirasvit\Related\Api\Data\BlockInterface;

;

class Block extends AbstractModel implements BlockInterface
{
    /**
     * {@inheritdoc}
     */
    protected function _construct()
    {
        $this->_init(ResourceModel\Block::class);
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->getData(self::ID);
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return $this->getData(self::NAME);
    }

    /**
     * {@inheritdoc}
     */
    public function setName($value)
    {
        return $this->setData(self::NAME, $value);
    }

    /**
     * {@inheritdoc}
     */
    public function getPriority()
    {
        return $this->getData(self::PRIORITY);
    }

    /**
     * {@inheritdoc}
     */
    public function setPriority($value)
    {
        return $this->setData(self::PRIORITY, $value);
    }

    /**
     * {@inheritdoc}
     */
    public function isActive()
    {
        return $this->getData(self::IS_ACTIVE);
    }

    /**
     * {@inheritdoc}
     */
    public function setIsActive($value)
    {
        return $this->setData(self::IS_ACTIVE, $value);
    }

    /**
     * {@inheritdoc}
     */
    public function getStoreIds()
    {
        return explode(',', $this->getData(self::STORE_IDS));
    }

    /**
     * {@inheritdoc}
     */
    public function setStoreIds($value)
    {
        if (!is_array($value)) {
            $value = explode(',', $value);
        }

        return $this->setData(self::STORE_IDS, implode(',', $value));
    }

    /**
     * {@inheritdoc}
     */
    public function getLayoutUpdateId()
    {
        return $this->getData(self::LAYOUT_UPDATE_ID);
    }

    /**
     * {@inheritdoc}
     */
    public function setLayoutUpdateId($value)
    {
        return $this->setData(self::LAYOUT_UPDATE_ID, $value);
    }

    /**
     * {@inheritdoc}
     */
    public function getLayoutPosition()
    {
        return $this->getData(self::LAYOUT_POSITION);
    }

    /**
     * {@inheritdoc}
     */
    public function setLayoutPosition($value)
    {
        return $this->setData(self::LAYOUT_POSITION, $value);
    }

    /**
     * {@inheritdoc}
     */
    public function isLayoutRemoveRelated()
    {
        return $this->getData(self::LAYOUT_REMOVE_RELATED);
    }

    /**
     * {@inheritdoc}
     */
    public function isLayoutRemoveUpSells()
    {
        return $this->getData(self::LAYOUT_REMOVE_UP_SELLS);
    }

    /**
     * {@inheritdoc}
     */
    public function isLayoutRemoveCrossSells()
    {
        return $this->getData(self::LAYOUT_REMOVE_CROSS_SELLS);
    }

    /**
     * {@inheritdoc}
     */
    public function getDisplayTitle()
    {
        return $this->getData(self::DISPLAY_TITLE);
    }

    /**
     * {@inheritdoc}
     */
    public function setDisplayTitle($value)
    {
        return $this->setData(self::DISPLAY_TITLE, $value);
    }

    /**
     * {@inheritdoc}
     */
    public function getDisplayProductsLimit()
    {
        return $this->getData(self::DISPLAY_PRODUCTS_LIMIT);
    }

    /**
     * {@inheritdoc}
     */
    public function setDisplayProductsLimit($value)
    {
        return $this->setData(self::DISPLAY_PRODUCTS_LIMIT, $value);
    }

    /**
     * {@inheritdoc}
     */
    public function getDisplayIsUseSlider()
    {
        return $this->getData(self::DISPLAY_IS_USE_SLIDER);
    }

    /**
     * {@inheritdoc}
     */
    public function setDisplayIsUseSlider($value)
    {
        return $this->setData(self::DISPLAY_IS_USE_SLIDER, $value);
    }

    /**
     * {@inheritdoc}
     */
    public function getDisplayProductsPerPage()
    {
        return $this->getData(self::DISPLAY_PRODUCTS_PER_PAGE);
    }

    /**
     * {@inheritdoc}
     */
    public function setDisplayProductsPerPage($value)
    {
        return $this->setData(self::DISPLAY_PRODUCTS_PER_PAGE, $value);
    }

    /**
     * {@inheritdoc}
     */
    public function getDisplayTemplate()
    {
        $template = $this->getData(self::DISPLAY_TEMPLATE);

        if (!$template || $template === 'default') {
            return 'Mirasvit_Related::block/default.phtml';
        }

        return $template;
    }

    /**
     * {@inheritdoc}
     */
    public function setDisplayTemplate($value)
    {
        return $this->setData(self::DISPLAY_TEMPLATE, $value);
    }

    /**
     * {@inheritdoc}
     */
    public function getRuleId()
    {
        return $this->getData(self::RULE_ID);
    }

    /**
     * {@inheritdoc}
     */
    public function setRuleId($value)
    {
        return $this->setData(self::RULE_ID, $value);
    }
}
