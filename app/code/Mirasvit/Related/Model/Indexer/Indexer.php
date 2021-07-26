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



namespace Mirasvit\Related\Model\Indexer;

class Indexer implements \Magento\Framework\Indexer\ActionInterface, \Magento\Framework\Mview\ActionInterface
{
    private $viewedTogetherFull;

    private $boughtTogetherFull;

    private $alsoBoughtFull;

    public function __construct(
        Action\ViewedTogetherFullReindex $viewedTogetherFull,
        Action\BoughtTogetherFullReindex $boughtTogetherFull,
        Action\AlsoBoughtFullReindex $alsoBoughtFull
    ) {
        $this->viewedTogetherFull = $viewedTogetherFull;
        $this->boughtTogetherFull = $boughtTogetherFull;
        $this->alsoBoughtFull     = $alsoBoughtFull;
    }

    public function executeFull()
    {
        $this->viewedTogetherFull->reindexAll();
        $this->boughtTogetherFull->reindexAll();
        $this->alsoBoughtFull->reindexAll();
    }

    public function execute($ids)
    {
    }

    public function executeList(array $ids)
    {
    }

    public function executeRow($id)
    {
    }
}
