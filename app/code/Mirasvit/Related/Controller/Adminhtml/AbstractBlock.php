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



namespace Mirasvit\Related\Controller\Adminhtml;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Registry;
use Mirasvit\Related\Api\Data\BlockInterface;
use Mirasvit\Related\Repository\BlockRepository;

abstract class AbstractBlock extends Action
{
    /**
     * @var BlockRepository
     */
    protected $blockRepository;

    /**
     * @var Context
     */
    protected $context;

    /**
     * @var Registry
     */
    protected $registry;

    public function __construct(
        BlockRepository $blockRepository,
        Registry $registry,
        Context $context
    ) {
        $this->blockRepository = $blockRepository;
        $this->registry        = $registry;
        $this->context         = $context;

        parent::__construct($context);
    }

    /**
     * @param \Magento\Backend\Model\View\Result\Page $resultPage
     *
     * @return \Magento\Backend\Model\View\Result\Page
     */
    protected function initPage($resultPage)
    {
        $resultPage->setActiveMenu('Magento_Backend::marketing');
        $resultPage->getConfig()->getTitle()->prepend(__('Related Products'));
        $resultPage->getConfig()->getTitle()->prepend(__('Product Blocks'));

        return $resultPage;
    }

    /**
     * @return BlockInterface
     */
    public function initModel()
    {
        $model = $this->blockRepository->create();

        if ($this->getRequest()->getParam(BlockInterface::ID)) {
            $model = $this->blockRepository->get($this->getRequest()->getParam(BlockInterface::ID));
        }

        $this->registry->register(BlockInterface::class, $model);

        return $model;
    }

    /**
     * {@inheritdoc}
     */
    protected function _isAllowed()
    {
        return $this->context->getAuthorization()->isAllowed('Mirasvit_Related::related');
    }
}
