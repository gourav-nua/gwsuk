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



namespace Mirasvit\Related\Ui\Rule\Form;

use Magento\Ui\DataProvider\AbstractDataProvider;
use Mirasvit\Related\Repository\RuleRepository;

class DataProvider extends AbstractDataProvider
{
    /**
     * @var RuleRepository
     */
    private $ruleRepository;

    public function __construct(
        RuleRepository $ruleRepository,
        $name,
        $primaryFieldName,
        $requestFieldName,
        array $meta = [],
        array $data = []
    ) {
        $this->ruleRepository = $ruleRepository;
        $this->collection     = $this->ruleRepository->getCollection();

        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
    }

    /**
     * {@inheritdoc}
     */
    public function getData()
    {
        $result = [];

        foreach ($this->ruleRepository->getCollection() as $model) {
            $data = $model->getData();

            $result[$model->getId()] = $data;
        }

        return $result;
    }
}
