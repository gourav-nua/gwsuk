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

interface IndexInterface
{
    const SOURCE_VIEWED_TOGETHER = 'viewed_together';
    const SOURCE_BOUGHT_TOGETHER = 'bought_together';
    const SOURCE_ALSO_BOUGHT     = 'also_bought';

    const TABLE_NAME = 'mst_related_index';

    const ID                = 'index_id';
    const SOURCE            = 'source';
    const STORE_ID          = 'store_id';
    const PRODUCT_ID        = 'product_id';
    const LINKED_PRODUCT_ID = 'linked_product_id';
    const SCORE             = 'score';
}