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
use Magento\Framework\Model\Context;
use Magento\Framework\Registry;
use Mirasvit\Related\Api\Data\RuleInterface;

class Rule extends AbstractModel implements RuleInterface
{
    /**
     * @var Rule\Rule
     */
    private $rule;

    private $ruleFactory;

    public function __construct(
        Rule\RuleFactory $ruleFactory,
        Context $context,
        Registry $registry
    ) {
        $this->ruleFactory = $ruleFactory;

        parent::__construct($context, $registry);
    }

    /**
     * {@inheritdoc}
     */
    protected function _construct()
    {
        $this->_init(ResourceModel\Rule::class);
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
    public function getSource()
    {
        return $this->getData(self::SOURCE);
    }

    /**
     * {@inheritdoc}
     */
    public function setSource($value)
    {
        return $this->setData(self::SOURCE, $value);
    }

    /**
     * {@inheritdoc}
     */
    public function getConditionsSerialized()
    {
        return $this->getData(self::CONDITIONS_SERIALIZED);
    }

    /**
     * {@inheritdoc}
     */
    public function setConditionsSerialized($value)
    {
        return $this->setData(self::CONDITIONS_SERIALIZED, $value);
    }

    /**
     * @return Rule\Rule
     */
    public function getRule()
    {
        if (!$this->rule) {
            $this->rule = $this->ruleFactory->create()
                ->setData(self::CONDITIONS_SERIALIZED, $this->getData(self::CONDITIONS_SERIALIZED));
        }

        return $this->rule;
    }

    public function getIsIncludeRelated()
    {
        return (bool)$this->getData(self::INCLUDE_RELATED);
    }

    public function setIsIncludeRelated($value)
    {
        return $this->setData(self::INCLUDE_RELATED, $value);
    }

    public function getIsIncludeUpSells()
    {
        return (bool)$this->getData(self::INCLUDE_UP_SELLS);
    }

    public function setIsIncludeUpSells($value)
    {
        return $this->setData(self::INCLUDE_UP_SELLS, $value);
    }

    public function getIsIncludeCrossSells()
    {
        return (bool)$this->getData(self::INCLUDE_CROSS_SELLS);
    }

    public function setIsIncludeCrossSells($value)
    {
        return $this->setData(self::INCLUDE_CROSS_SELLS, $value);
    }
}
