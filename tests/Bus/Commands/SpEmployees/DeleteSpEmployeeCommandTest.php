<?php

/*
 * This file is part of Cachet.
 *
 * (c) Alt Three Services Limited
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CachetHQ\Tests\Cachet\Bus\Commands\SpEmployees;

use AltThree\TestBench\CommandTrait;
use CachetHQ\Cachet\Bus\Commands\SpEmployees\DeleteSpEmployeeCommand;
use CachetHQ\Cachet\Bus\Handlers\Commands\SpEmployees\DeleteSpEmployeeCommandHandler;
use CachetHQ\Cachet\Models\SpEmployees;
use CachetHQ\Cachet\Models\UserGroup;
use CachetHQ\Tests\Cachet\AbstractTestCase;

/**
 * This is the remove component command test class.
 *
 * @author James Brooks <james@alt-three.com>
 * @author Graham Campbell <graham@alt-three.com>
 */
class DeleteSpEmployeeCommandTest extends AbstractTestCase
{
    use CommandTrait;

    protected function getObjectAndParams()
    {
        $params = ['employee' => new SpEmployees()];
        $object = new DeleteSpEmployeeCommand($params['employee']);

        return compact('params', 'object');
    }

    protected function getHandlerClass()
    {
        return DeleteSpEmployeeCommandHandler::class;
    }
}
