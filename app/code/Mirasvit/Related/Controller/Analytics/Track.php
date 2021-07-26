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



namespace Mirasvit\Related\Controller\Analytics;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Mirasvit\Core\Service\SerializeService;
use Mirasvit\Related\Repository\AnalyticsRepository;


class Track extends Action
{
    private $analyticsRepository;

    public function __construct(
        AnalyticsRepository $analyticsRepository,
        Context $context
    ) {
        $this->analyticsRepository = $analyticsRepository;

        parent::__construct($context);
    }

    /**
     * {@inheritdoc}
     */
    public function execute()
    {
        $action    = $this->getRequest()->getParam('action');
        $blockId   = $this->getRequest()->getParam('block_id');
        $sessionId = $this->getRequest()->getParam('session_id');

        if ($action && $blockId) {
            $model = $this->analyticsRepository->create();
            $model->setAction($action)
                ->setSessionId($sessionId)
                ->setBlockId($blockId)
                ->setValue(1);

            $this->analyticsRepository->save($model);
        }

        /** @var \Magento\Framework\App\Response\Http $response */
        $response = $this->getResponse();
        $response->representJson(SerializeService::encode([
            'success' => true,
        ]));
    }
}
