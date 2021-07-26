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

class Save extends AbstractBlock
{
    /**
     * {@inheritdoc}
     */
    public function execute()
    {
        $id = $this->getRequest()->getParam(BlockInterface::ID);

        $resultRedirect = $this->resultRedirectFactory->create();

        $data = $this->getRequest()->getParams();

        if ($data) {
            $model = $this->initModel();

            if (!$model->getId() && $id) {
                $this->messageManager->addErrorMessage(__('This block no longer exists.'));

                return $resultRedirect->setPath('*/*/');
            }

            $data = $this->filterPostData($data);

            foreach ($data as $key => $value) {
                $model->setDataUsingMethod($key, $value);
            }

            try {
                $this->blockRepository->save($model);

                $this->messageManager->addSuccessMessage(__('You have saved the block.'));

                if ($this->getRequest()->getParam('back') == 'edit') {
                    return $resultRedirect->setPath('*/*/edit', [BlockInterface::ID => $model->getId()]);
                }

                return $this->context->getResultRedirectFactory()->create()->setPath('*/*/');
            } catch (\Exception $e) {
                $this->messageManager->addErrorMessage($e->getMessage());

                return $resultRedirect->setPath(
                    '*/*/edit',
                    [BlockInterface::ID => $this->getRequest()->getParam(BlockInterface::ID)]
                );
            }
        } else {
            $resultRedirect->setPath('*/*/');
            $this->messageManager->addErrorMessage('No data to save.');

            return $resultRedirect;
        }
    }

    private function filterPostData(array $data)
    {
        if (isset($data['layout_position_predefined']) && (bool)$data['layout_position_predefined'] === false) {
            $data[BlockInterface::LAYOUT_POSITION] = $data['layout_position_custom'];
        }

        return $data;
    }
}
