<?php

/*
 * This file is part of Cachet.
 *
 * (c) Alt Three Services Limited
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CachetHQ\Tests\Cachet\Providers;

use AltThree\TestBench\ServiceProviderTrait;
use CachetHQ\Cachet\Settings\Cache;
use CachetHQ\Cachet\Settings\Repository;
use CachetHQ\Tests\Cachet\AbstractTestCase;
use ReflectionClass;

/**
 * This is the config service provider test class.
 *
 * @author Graham Campbell <graham@alt-three.com>
 */
class ConfigServiceProviderTest extends AbstractTestCase
{
    use ServiceProviderTrait {
        ServiceProviderTrait::getServiceProviderClass as traitGetServiceProviderClass;
    }

    protected function GetServiceProviderClass($app)
    {
        $split = explode('\\', (new ReflectionClass($this))->getName());
        $class = substr(end($split), 0, -4);

        return "{$split[0]}\\{$split[2]}\\Providers\\{$class}";
    }

    public function testCacheIsInjectable()
    {
        $this->assertIsInjectable(Cache::class);
    }

    public function testRepositoryIsInjectable()
    {
        $this->assertIsInjectable(Repository::class);
    }
}
