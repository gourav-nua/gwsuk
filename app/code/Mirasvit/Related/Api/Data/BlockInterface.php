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

interface BlockInterface
{
    const TABLE_NAME = 'mst_related_block';

    const ID = 'block_id';

    const NAME      = 'name';
    const PRIORITY  = 'priority';
    const IS_ACTIVE = 'is_active';
    const STORE_IDS = 'store_ids';

    const LAYOUT_UPDATE_ID          = 'layout_update_id';
    const LAYOUT_POSITION           = 'layout_position';
    const LAYOUT_CONDITIONS         = 'layout_conditions';
    const LAYOUT_REMOVE_RELATED     = 'layout_remove_related';
    const LAYOUT_REMOVE_CROSS_SELLS = 'layout_remove_cross_sells';
    const LAYOUT_REMOVE_UP_SELLS    = 'layout_remove_up_sells';

    const DISPLAY_TITLE             = 'display_title';
    const DISPLAY_MODE              = 'display_mode';
    const DISPLAY_PRODUCTS_LIMIT    = 'display_products_limit';
    const DISPLAY_IS_USE_SLIDER     = 'display_is_use_slider';
    const DISPLAY_PRODUCTS_PER_PAGE = 'display_products_per_page';
    const DISPLAY_TEMPLATE          = 'display_template';

    const RULE_ID = 'rule_id';

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
     * @return int
     */
    public function getPriority();

    /**
     * @param int $value
     *
     * @return $this
     */
    public function setPriority($value);

    /**
     * @return bool
     */
    public function isActive();

    /**
     * @param bool $value
     *
     * @return $this
     */
    public function setIsActive($value);

    /**
     * @return int[]
     */
    public function getStoreIds();

    /**
     * @param int[] $value
     *
     * @return $this
     */
    public function setStoreIds($value);

    /**
     * @return int
     */
    public function getLayoutUpdateId();

    /**
     * @param int $value
     *
     * @return $this
     */
    public function setLayoutUpdateId($value);

    /**
     * @return string
     */
    public function getLayoutPosition();

    /**
     * @param string $value
     *
     * @return $this
     */
    public function setLayoutPosition($value);

    /**
     * @return bool
     */
    public function isLayoutRemoveRelated();

    /**
     * @return bool
     */
    public function isLayoutRemoveCrossSells();

    /**
     * @return bool
     */
    public function isLayoutRemoveUpSells();

    /**
     * @return string
     */
    public function getDisplayTitle();

    /**
     * @param string $value
     *
     * @return $this
     */
    public function setDisplayTitle($value);

    /**
     * @return int
     */
    public function getDisplayProductsLimit();

    /**
     * @param int $value
     *
     * @return $this
     */
    public function setDisplayProductsLimit($value);

    /**
     * @return bool
     */
    public function getDisplayIsUseSlider();

    /**
     * @param bool $value
     *
     * @return $this
     */
    public function setDisplayIsUseSlider($value);

    /**
     * @return int
     */
    public function getDisplayProductsPerPage();

    /**
     * @param int $value
     *
     * @return $this
     */
    public function setDisplayProductsPerPage($value);

    /**
     * @return string
     */
    public function getDisplayTemplate();

    /**
     * @param string $value
     *
     * @return $this
     */
    public function setDisplayTemplate($value);

    /**
     * @return int
     */
    public function getRuleId();

    /**
     * @param int $value
     *
     * @return $this
     */
    public function setRuleId($value);

    /**
     * {@inheritDoc}
     */
    public function getData($key = '');

    /**
     * {@inheritDoc}
     */
    public function setDataUsingMethod($key, $value);
}
