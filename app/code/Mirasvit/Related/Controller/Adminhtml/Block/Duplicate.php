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



namespace Mirasvit\Related\Controller\Adminhtml\Block;

use Mirasvit\Related\Api\Data\BlockInterface;
use Mirasvit\Related\Controller\Adminhtml\AbstractBlock;

class Duplicate extends AbstractBlock
{
    /**
     * {@inheritdoc}
     */
    public function execute()
    {
        $id = $this->getRequest()->getParam(BlockInterface::ID);

        $resultRedirect = $this->resultRedirectFactory->create();

        $master = $this->initModel();

        if (!$master->getId() && $id) {
            $this->messageManager->addErrorMessage(__('This block no longer exists.'));

            return $resultRedirect->setPath('*/*/');
        }

        $slave = $this->blockRepository->create();

        foreach ($master->getData() as $key => $value) {
            $slave->setDataUsingMethod($key, $value);
        }
        $slave->setDataUsingMethod(BlockInterface::ID, null);
        $slave->setDataUsingMethod(BlockInterface::LAYOUT_UPDATE_ID, null);


        try {
            $this->blockRepository->save($slave);

            $this->messageManager->addSuccessMessage(__('You have duplicated the block.'));

            return $resultRedirect->setPath('*/*/edit', [BlockInterface::ID => $slave->getId()]);
        } catch (\Exception $e) {
            $this->messageManager->addErrorMessage($e->getMessage());

            return $resultRedirect->setPath('*/*');
        }
    }
}
