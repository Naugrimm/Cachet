<?php

namespace CachetHQ\Tests\Cachet\Bus\Commands\UserGroup;

use AltThree\TestBench\CommandTrait;
use CachetHQ\Cachet\Bus\Commands\Component\UpdateComponentCommand;
use CachetHQ\Cachet\Bus\Commands\UserGroup\UpdateUserGroupCommand;
use CachetHQ\Cachet\Bus\Handlers\Commands\Component\UpdateComponentCommandHandler;
use CachetHQ\Cachet\Bus\Handlers\Commands\UserGroup\UpdateUserGroupCommandHandler;
use CachetHQ\Cachet\Models\Component;
use CachetHQ\Cachet\Models\Subscriber;
use CachetHQ\Cachet\Models\UserGroup;
use CachetHQ\Tests\Cachet\AbstractTestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;


class UpdateUserGroupCommandTest extends AbstractTestCase
{
    use DatabaseMigrations;
    use CommandTrait;

    protected function getObjectAndParams()
    {
        $userGroup = new UserGroup(['userGroupName' => 'Blab']);

        $params = [
            'userGroup' => $userGroup,
            'userGroupName'        => 'Test',
        ];


        $object = new UpdateUserGroupCommand(
            $params['userGroup'],
            $params['userGroupName']
        );

        return compact('params', 'object');
    }

    protected function objectHasRules()
    {
        return true;
    }

    protected function getHandlerClass()
    {
        return UpdateUserGroupCommandHandler::class;
    }
}
