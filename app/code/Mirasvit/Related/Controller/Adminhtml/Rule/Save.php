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



namespace Mirasvit\Related\Controller\Adminhtml\Rule;

use Mirasvit\Core\Service\SerializeService;
use Mirasvit\Related\Api\Data\RuleInterface;
use Mirasvit\Related\Controller\Adminhtml\AbstractRule;

class Save extends AbstractRule
{
    /**
     * {@inheritdoc}
     */
    public function execute()
    {
        $id = $this->getRequest()->getParam(RuleInterface::ID);

        $resultRedirect = $this->resultRedirectFactory->create();

        $data = $this->getRequest()->getParams();

        if ($data) {
            $model = $this->initModel();

            if (!$model->getId() && $id) {
                $this->messageManager->addErrorMessage(__('This rule no longer exists.'));

                return $resultRedirect->setPath('*/*/');
            }

            $data = $this->filterPostData($data, $model);

            foreach ($data as $key => $value) {
                $model->setDataUsingMethod($key, $value);
            }

            try {
                $this->ruleRepository->save($model);

                $this->messageManager->addSuccessMessage(__('You saved the rule.'));

                if ($this->getRequest()->getParam('back') == 'edit') {
                    return $resultRedirect->setPath('*/*/edit', [RuleInterface::ID => $model->getId()]);
                }

                return $this->context->getResultRedirectFactory()->create()->setPath('*/*/');
            } catch (\Exception $e) {
                $this->messageManager->addErrorMessage($e->getMessage());

                return $resultRedirect->setPath(
                    '*/*/edit',
                    [RuleInterface::ID => $this->getRequest()->getParam(RuleInterface::ID)]
                );
            }
        } else {
            $resultRedirect->setPath('*/*/');
            $this->messageManager->addErrorMessage('No data to save.');

            return $resultRedirect;
        }
    }

    /**
     * @param array $rawData
     *
     * @return array
     */
    private function filterPostData(array $rawData, RuleInterface $model)
    {
        $rule = $model->getRule();

        if (isset($rawData['rule']) && isset($rawData['rule']['conditions'])) {

            $rule->loadPost(['conditions' => $rawData['rule']['conditions']]);

            $conditions = $rule->getConditions()->asArray();

            $conditions = SerializeService::encode($conditions);

            $rawData[RuleInterface::CONDITIONS_SERIALIZED] = $conditions;
        } else {
            $rawData[RuleInterface::CONDITIONS_SERIALIZED] = SerializeService::encode([]);
        }

        return $rawData;
    }
}
