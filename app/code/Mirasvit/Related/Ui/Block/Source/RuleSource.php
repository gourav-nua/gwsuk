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



namespace Mirasvit\Related\Ui\Block\Source;

use Magento\Framework\Data\OptionSourceInterface;
use Mirasvit\Related\Repository\RuleRepository;

class RuleSource implements OptionSourceInterface
{
    private $ruleRepository;

    public function __construct(
        RuleRepository $ruleRepository
    ) {
        $this->ruleRepository = $ruleRepository;
    }

    public function toOptionArray()
    {
        $options = [];

        foreach ($this->ruleRepository->getCollection() as $rule) {

            $options[] = [
                'value' => $rule->getId(),
                'label' => $rule->getName(),
            ];
        }

        return $options;
    }
}