<?php

/*
 * This file is part of Cachet.
 *
 * (c) Alt Three Services Limited
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CachetHQ\Tests\Cachet\Bus\Events\IncidentUpdate;

use AltThree\TestBench\EventTrait;
use CachetHQ\Cachet\Bus\Events\IncidentUpdate\IncidentUpdateEventInterface;
use CachetHQ\Tests\Cachet\AbstractTestCase;
use ReflectionClass;

abstract class AbstractIncidentUpdateEventTestCase extends AbstractTestCase
{
    use EventTrait {
        EventTrait::getEventServiceProvider as traitGetEventServiceProvider;
    }

    protected function getEventServiceProvider()
    {
        $split = explode('\\', (new ReflectionClass($this))->getName());

        return "{$split[0]}\\{$split[2]}\\Providers\\EventServiceProvider";
    }

    protected function getEventInterfaces()
    {
        return [IncidentUpdateEventInterface::class];
    }
}
