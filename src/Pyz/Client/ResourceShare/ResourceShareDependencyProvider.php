<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Client\ResourceShare;

use Spryker\Client\PersistentCartShare\Plugin\ResourceShare\QuoteFallbackActivatorStrategyPlugin;
use Spryker\Client\ResourceShare\ResourceShareDependencyProvider as SprykerResourceShareDependencyProvider;
use Spryker\Client\SharedCart\Plugin\ResourceShare\SwitchDefaultCartResourceShareActivatorStrategyPlugin;

class ResourceShareDependencyProvider extends SprykerResourceShareDependencyProvider
{
    /**
     * @return \Spryker\Client\ResourceShareExtension\Dependency\Plugin\ResourceShareActivatorStrategyPluginInterface[]
     */
    protected function getResourceShareActivatorStrategyPlugins(): array
    {
        return [
            new SwitchDefaultCartResourceShareActivatorStrategyPlugin(),
            new QuoteFallbackActivatorStrategyPlugin(),
        ];
    }
}
