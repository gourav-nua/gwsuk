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

use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Data\OptionSourceInterface;
use Magento\Framework\Filesystem;
use Magento\Framework\Module\Dir;

class TemplateSource implements OptionSourceInterface
{
    private $filesystem;

    private $dir;

    public function __construct(
        Filesystem $filesystem,
        Dir $dir
    ) {
        $this->filesystem = $filesystem;
        $this->dir        = $dir;
    }

    public function toOptionArray()
    {
        $options = [
            [
                'value' => 'Mirasvit_Related::block/default.phtml',
                'label' => __('Default'),
            ],
        ];

        $appDir = $this->filesystem->getDirectoryRead(DirectoryList::APP)->getAbsolutePath();
        $toScan = [
            $appDir . 'design/frontend/*/*/Mirasvit_Related/templates/block/*.phtml',
            $this->dir->getDir('Mirasvit_Related', 'view') . '/frontend/templates/block/*.phtml',
        ];

        foreach ($toScan as $pattern) {
            foreach (glob($pattern) as $filename) {
                $basename = pathinfo($filename)['basename'];
                $name     = pathinfo($filename)['filename'];

                if ($name === 'default') {
                    continue;
                }

                $options[] = [
                    'value' => 'Mirasvit_Related::block/' . $basename,
                    'label' => ucfirst($name),
                ];
            }
        }

        return $options;
    }
}
