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



namespace Mirasvit\Related\Plugin\Sales\Model\Service\OrderService;

use Magento\Framework\Stdlib\CookieManagerInterface;
use Magento\Sales\Api\Data\OrderInterface;
use Mirasvit\Related\Api\Data\AnalyticsInterface;
use Mirasvit\Related\Repository\AnalyticsRepository;

/**
 * @see \Magento\Sales\Model\Service\OrderService
 */
class AnalyticsTrackOrderPlugin
{
    private $analyticsRepository;

    private $cookieManager;

    public function __construct(
        AnalyticsRepository $analyticsRepository,
        CookieManagerInterface $cookieManager
    ) {
        $this->analyticsRepository = $analyticsRepository;
        $this->cookieManager       = $cookieManager;
    }

    /**
     * @param object         $subject
     * @param OrderInterface $order
     *
     * @return object
     */
    public function afterPlace($subject, $order)
    {
        $click = $this->getLastClick();

        if (!$click) {
            return $order;
        }

        $sessionId = $this->getSessionId();
        $blockId   = $click->getBlockId();

        if (!$sessionId || !$blockId) {
            return $order;
        }

        $model = $this->analyticsRepository->create();
        $model->setAction(AnalyticsInterface::ACTION_ORDER)
            ->setBlockId($blockId)
            ->setValue(1)
            ->setSessionId($sessionId);
        $this->analyticsRepository->save($model);

        $model = $this->analyticsRepository->create();
        $model->setAction(AnalyticsInterface::ACTION_REVENUE)
            ->setBlockId($blockId)
            ->setValue($order->getBaseGrandTotal())
            ->setSessionId($sessionId);
        $this->analyticsRepository->save($model);

        return $order;
    }

    /**
     * @return AnalyticsInterface|false
     */
    private function getLastClick()
    {
        $sessionId = $this->getSessionId();

        if (!$sessionId) {
            return false;
        }

        $collection = $this->analyticsRepository->getCollection();
        $collection->addFieldToFilter(AnalyticsInterface::SESSION_ID, $sessionId)
            ->addFieldToFilter(AnalyticsInterface::ACTION, AnalyticsInterface::ACTION_CLICK);

        $item = $collection->getFirstItem();

        return $item->getId() ? $item : false;
    }

    private function getSessionId()
    {
        return $this->cookieManager->getCookie(AnalyticsInterface::SESSION_COOKIE);
    }
}
