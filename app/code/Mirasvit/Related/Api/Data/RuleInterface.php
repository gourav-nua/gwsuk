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



namespace Mirasvit\Related\Api\Data;

interface RuleInterface
{
    const TABLE_NAME = 'mst_related_rule';

    const SOURCE_ALL             = 'all';
    const SOURCE_BOUGHT_TOGETHER = IndexInterface::SOURCE_BOUGHT_TOGETHER;
    const SOURCE_ALSO_BOUGHT     = IndexInterface::SOURCE_ALSO_BOUGHT;
    const SOURCE_VIEWED_TOGETHER = IndexInterface::SOURCE_VIEWED_TOGETHER;

    const ID                    = 'rule_id';
    const NAME                  = 'name';
    const SOURCE                = 'source';
    const CONDITIONS_SERIALIZED = 'conditions_serialized';
    const INCLUDE_RELATED       = 'include_related';
    const INCLUDE_UP_SELLS      = 'include_up_sells';
    const INCLUDE_CROSS_SELLS   = 'include_cross_sells';

    /**
     * @return int
     */
    public function getId();

    /**
     * @return string
     */
    public function getName();

    /**
     * @param string $value
     *
     * @return $this
     */
    public function setName($value);

    /**
     * @return string
     */
    public function getSource();

    /**
     * @param string $value
     *
     * @return $this
     */
    public function setSource($value);

    /**
     * @return string
     */
    public function getConditionsSerialized();

    /**
     * @param string $value
     *
     * @return $this
     */
    public function setConditionsSerialized($value);

    /**
     * @return \Mirasvit\Related\Model\Rule\Rule
     */
    public function getRule();

    /**
     * {@inheritDoc}
     */
    public function getData($key = '');

    /**
     * {@inheritDoc}
     */
    public function setDataUsingMethod($key, $value);

    /**
     * @return bool
     */
    public function getIsIncludeRelated();

    /**
     * @param int $value
     *
     * @return $this
     */
    public function setIsIncludeRelated($value);

    /**
     * @return bool
     */
    public function getIsIncludeUpSells();

    /**
     * @param int $value
     *
     * @return $this
     */
    public function setIsIncludeUpSells($value);

    /**
     * @return bool
     */
    public function getIsIncludeCrossSells();

    /**
     * @param int $value
     *
     * @return $this
     */
    public function setIsIncludeCrossSells($value);
}
