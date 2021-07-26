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

interface AnalyticsInterface
{
    const SESSION_COOKIE    = 'mst_related_session_id';

    const ACTION_IMPRESSION = 'impression';
    const ACTION_CLICK      = 'click';
    const ACTION_ORDER      = 'order';
    const ACTION_REVENUE    = 'revenue';

    const TABLE_NAME = 'mst_related_analytics';

    const ID         = 'analytics_id';
    const BLOCK_ID   = 'block_id';
    const ACTION     = 'action';
    const VALUE      = 'value';
    const SESSION_ID = 'session_id';
    const CREATED_AT = 'created_at';

    /**
     * @return int
     */
    public function getId();

    /**
     * @return int
     */
    public function getBlockId();

    /**
     * @param int $value
     *
     * @return $this
     */
    public function setBlockId($value);

    /**
     * @return string
     */
    public function getAction();

    /**
     * @param string $value
     *
     * @return $this
     */
    public function setAction($value);

    /**
     * @return string
     */
    public function getValue();

    /**
     * @param string $value
     *
     * @return $this
     */
    public function setValue($value);

    /**
     * @return string
     */
    public function getSessionId();

    /**
     * @param string $value
     *
     * @return $this
     */
    public function setSessionId($value);

    /**
     * @return string
     */
    public function getCreatedAt();
}