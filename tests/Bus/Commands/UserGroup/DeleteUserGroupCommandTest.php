<?php

/*
 * This file is part of Cachet.
 *
 * (c) Alt Three Services Limited
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CachetHQ\Tests\Cachet\Bus\Commands\UserGroup;

use AltThree\TestBench\CommandTrait;
use CachetHQ\Cachet\Bus\Commands\Component\RemoveComponentCommand;
use CachetHQ\Cachet\Bus\Commands\UserGroup\DeleteUserGroupCommand;
use CachetHQ\Cachet\Bus\Handlers\Commands\Component\RemoveComponentCommandHandler;
use CachetHQ\Cachet\Bus\Handlers\Commands\UserGroup\DeleteUserGroupCommandHandler;
use CachetHQ\Cachet\Models\Component;
use CachetHQ\Cachet\Models\UserGroup;
use CachetHQ\Tests\Cachet\AbstractTestCase;

/**
 * This is the remove component command test class.
 *
 * @author James Brooks <james@alt-three.com>
 * @author Graham Campbell <graham@alt-three.com>
 */
class DeleteUserGroupCommandTest extends AbstractTestCase
{
    use CommandTrait;

    protected function getObjectAndParams()
    {
        $params = ['userGroup' => new UserGroup()];
        $object = new DeleteUserGroupCommand($params['userGroup']);

        return compact('params', 'object');
    }

    protected function getHandlerClass()
    {
        return DeleteUserGroupCommandHandler::class;
    }
}
