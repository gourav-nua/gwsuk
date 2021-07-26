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

use Mirasvit\Related\Api\Data\RuleInterface;
use Mirasvit\Related\Controller\Adminhtml\AbstractRule;

class Duplicate extends AbstractRule
{
    /**
     * {@inheritdoc}
     */
    public function execute()
    {
        $id = $this->getRequest()->getParam(RuleInterface::ID);

        $resultRedirect = $this->resultRedirectFactory->create();

        $master = $this->initModel();

        if (!$master->getId() && $id) {
            $this->messageManager->addErrorMessage(__('This rule no longer exists.'));

            return $resultRedirect->setPath('*/*/');
        }

        $slave = $this->ruleRepository->create();

        foreach ($master->getData() as $key => $value) {
            $slave->setDataUsingMethod($key, $value);
        }
        $slave->setDataUsingMethod(RuleInterface::ID, null);

        try {
            $this->ruleRepository->save($slave);

            $this->messageManager->addSuccessMessage(__('You have duplicated the rule.'));

            return $resultRedirect->setPath('*/*/edit', [RuleInterface::ID => $slave->getId()]);
        } catch (\Exception $e) {
            $this->messageManager->addErrorMessage($e->getMessage());

            return $resultRedirect->setPath('*/*');
        }
    }
}
