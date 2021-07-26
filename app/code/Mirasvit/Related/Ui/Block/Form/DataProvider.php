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



namespace Mirasvit\Related\Ui\Block\Form;

use Magento\Ui\DataProvider\AbstractDataProvider;
use Mirasvit\Related\Api\Data\BlockInterface;
use Mirasvit\Related\Repository\BlockRepository;
use Mirasvit\Related\Service\LayoutXmlGeneratorService;
use Mirasvit\Related\Ui\Block\Source\PositionSource;

class DataProvider extends AbstractDataProvider
{
    /**
     * @var BlockRepository
     */
    private $blockRepository;

    private $xmlGeneratorService;

    private $positionSource;

    public function __construct(
        BlockRepository $blockRepository,
        LayoutXmlGeneratorService $xmlGeneratorService,
        PositionSource $positionSource,
        $name,
        $primaryFieldName,
        $requestFieldName,
        array $meta = [],
        array $data = []
    ) {
        $this->blockRepository     = $blockRepository;
        $this->xmlGeneratorService = $xmlGeneratorService;
        $this->positionSource      = $positionSource;
        $this->collection          = $this->blockRepository->getCollection();

        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
    }

    /**
     * {@inheritdoc}
     */
    public function getData()
    {
        $result = [];

        foreach ($this->blockRepository->getCollection() as $model) {
            $data = $model->getData();

            $data['layout_xml'] = $this->xmlGeneratorService->getXml($model);

            $data[BlockInterface::LAYOUT_POSITION . '_predefined'] = $this->positionSource->isValueDefined($model->getLayoutPosition()) ? '1' : '0';
            $data[BlockInterface::LAYOUT_POSITION . '_custom']     = $model->getLayoutPosition();

            $result[$model->getId()] = $data;
        }

        return $result;
    }
}
