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

use Magento\Framework\EntityManager\EntityManager;
use Mirasvit\Related\Api\Data\BlockInterface;
use Mirasvit\Related\Api\Data\BlockInterfaceFactory;
use Mirasvit\Related\Model\ResourceModel\Block\CollectionFactory;

class BlockRepository
{
    private $entityManager;

    private $collectionFactory;

    private $factory;

    private $layoutUpdateRepository;

    public function __construct(
        EntityManager $entityManager,
        CollectionFactory $collectionFactory,
        BlockInterfaceFactory $factory,
        LayoutUpdateRepository $layoutUpdateRepository
    ) {
        $this->entityManager          = $entityManager;
        $this->collectionFactory      = $collectionFactory;
        $this->factory                = $factory;
        $this->layoutUpdateRepository = $layoutUpdateRepository;
    }

    /**
     * @return BlockInterface[]|\Mirasvit\Related\Model\ResourceModel\Block\Collection
     */
    public function getCollection()
    {
        return $this->collectionFactory->create();
    }

    /**
     * @return BlockInterface
     */
    public function create()
    {
        return $this->factory->create();
    }

    /**
     * @param int $id
     *
     * @return BlockInterface|false
     */
    public function get($id)
    {
        $model = $this->create();
        $model = $this->entityManager->load($model, $id);

        if (!$model->getId()) {
            return false;
        }

        return $model;
    }

    /**
     * @param BlockInterface $model
     *
     * @return BlockInterface
     */
    public function save(BlockInterface $model)
    {
        if (!$model->getId()) {
            $this->entityManager->save($model);
        }

        $model = $this->layoutUpdateRepository->save($model);

        return $this->entityManager->save($model);
    }

    /**
     * @param BlockInterface $model
     */
    public function delete(BlockInterface $model)
    {
        $this->layoutUpdateRepository->delete($model);
        $this->entityManager->delete($model);
    }
}
