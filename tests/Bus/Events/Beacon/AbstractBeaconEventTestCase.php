<?php

/*
 * This file is part of Cachet.
 *
 * (c) Alt Three Services Limited
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CachetHQ\Tests\Cachet\Bus\Events\Beacon;

use AltThree\TestBench\EventTrait;
use CachetHQ\Cachet\Bus\Events\Beacon\BeaconEventInterface;
use CachetHQ\Tests\Cachet\AbstractTestCase;
use ReflectionClass;

/**
 * This is the abstract beacon event test case.
 *
 * @author James Brooks <james@alt-three.com>
 */
abstract class AbstractBeaconEventTestCase extends AbstractTestCase
{
    use EventTrait {
        EventTrait::getEventServiceProvider as traitGetEventServiceProvider;
    }

    protected function getEventInterfaces()
    {
        return [BeaconEventInterface::class];
    }

    protected function getEventServiceProvider()
    {
        $split = explode('\\', (new ReflectionClass($this))->getName());

        return "{$split[0]}\\{$split[2]}\\Providers\\EventServiceProvider";
    }
}
