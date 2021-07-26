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



namespace Mirasvit\Related\Repository;

use Magento\Store\Model\StoreManagerInterface;
use Magento\Widget\Model\Layout\Update as LayoutUpdate;
use Magento\Widget\Model\Layout\UpdateFactory as LayoutUpdateFactory;
use Magento\Widget\Model\ResourceModel\Layout\Link\CollectionFactory as LinkCollectionFactory;
use Mirasvit\Related\Api\Data\BlockInterface;
use Mirasvit\Related\Model\Config;
use Mirasvit\Related\Service\LayoutPositionInterpreterService;
use Mirasvit\Related\Service\LayoutXmlGeneratorService;

class LayoutUpdateRepository
{
    private $layoutUpdateFactory;

    private $linkCollectionFactory;

    private $xmlGeneratorService;

    private $positionInterpreter;

    private $storeManager;

    private $config;

    public function __construct(
        LayoutUpdateFactory $layoutUpdateFactory,
        LinkCollectionFactory $linkCollectionFactory,
        LayoutXmlGeneratorService $xmlGeneratorService,
        LayoutPositionInterpreterService $positionInterpreter,
        StoreManagerInterface $storeManager,
        Config $config
    ) {
        $this->layoutUpdateFactory   = $layoutUpdateFactory;
        $this->linkCollectionFactory = $linkCollectionFactory;
        $this->xmlGeneratorService   = $xmlGeneratorService;
        $this->positionInterpreter   = $positionInterpreter;
        $this->storeManager          = $storeManager;
        $this->config                = $config;
    }

    public function save(BlockInterface $block)
    {
        $layoutUpdate = $this->getLayoutUpdate($block->getLayoutUpdateId());

        $position = $this->positionInterpreter->decode($block->getLayoutPosition());

        $layoutUpdate->setHandle($position['handle'])
            ->setXml($this->xmlGeneratorService->getXml($block));
        $layoutUpdate->save();

        $block->setLayoutUpdateId($layoutUpdate->getId());

        $this->ensureLinks($block, $layoutUpdate);

        return $block;
    }

    public function delete(BlockInterface $block)
    {
        $update = $this->getLayoutUpdate($block->getLayoutUpdateId());
        $update->delete();
    }

    private function ensureLinks(BlockInterface $block, LayoutUpdate $layoutUpdate)
    {
        $links = $this->linkCollectionFactory->create();
        $links->addFieldToFilter('layout_update_id', $layoutUpdate->getId());
        foreach ($links as $link) {
            $link->delete();
        }

        $storeIds = $block->getStoreIds();
        if (in_array(0, $storeIds)) {
            $storeIds = [];
            foreach ($this->storeManager->getStores() as $store) {
                $storeIds[] = $store->getId();
            }

            $storeIds = array_unique($storeIds);
        }

        foreach ($storeIds as $storeId) {
            $layoutUpdate->setStoreId($storeId)
                ->setThemeId($this->config->getThemeId($storeId))
                ->save();
        }
    }

    private function getLayoutUpdate($updateId)
    {
        $layoutUpdate = $this->layoutUpdateFactory->create();

        if ($updateId) {
            $layoutUpdate->load($updateId);
        }

        return $layoutUpdate;
    }
}