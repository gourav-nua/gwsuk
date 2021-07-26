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



namespace Mirasvit\Related\Service;

class LayoutPositionInterpreterService
{
    public function encode($handle, $container, $before = '', $after = '')
    {
        return implode('/', [$handle, $container, $before, $after]);
    }

    public function decode($position)
    {
        $arr = explode('/', $position);

        return [
            'handle'    => isset($arr[0]) ? $arr[0] : '',
            'container' => isset($arr[1]) ? $arr[1] : '',
            'before'    => isset($arr[2]) ? $arr[2] : '',
            'after'     => isset($arr[3]) ? $arr[3] : '',
        ];
    }
}