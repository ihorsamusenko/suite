<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\CmsSlotBlockGui;

use Spryker\Zed\CategoryGui\Communication\Plugin\CategorySlotBlockConditionFormPlugin;
use Spryker\Zed\CmsSlotBlockGui\CmsSlotBlockGuiDependencyProvider as SprykerCmsSlotBlockGuiDependencyProvider;

class CmsSlotBlockGuiDependencyProvider extends SprykerCmsSlotBlockGuiDependencyProvider
{
    /**
     * @return \Spryker\Zed\CmsSlotBlockGuiExtension\Communication\Plugin\CmsSlotBlockGuiConditionFormPluginInterface[]
     */
    protected function getCmsSlotBlockFormPlugins(): array
    {
        return [
            new CategorySlotBlockConditionFormPlugin(),
        ];
    }
}
