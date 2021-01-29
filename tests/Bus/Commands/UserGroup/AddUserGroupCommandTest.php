<?php


namespace CachetHQ\Tests\Cachet\Bus\Commands\UserGroup;

use AltThree\TestBench\CommandTrait;
use CachetHQ\Cachet\Bus\Commands\UserGroup\AddUserGroupCommand;
use CachetHQ\Cachet\Bus\Handlers\Commands\UserGroup\AddUserGroupCommandHandler;
use CachetHQ\Tests\Cachet\AbstractTestCase;


class AddUserGroupCommandTest extends AbstractTestCase
{
    use CommandTrait;

    protected function getObjectAndParams()
    {
        $params = [
            'name'        => 'Test',
        ];

        $object = new AddUserGroupCommand(
            $params['name']
        );

       return compact('params', 'object');
    }

    protected function objectHasRules()
    {
        return true;
    }

    protected function getHandlerClass()
    {
        return AddUserGroupCommandHandler::class;
    }
}
