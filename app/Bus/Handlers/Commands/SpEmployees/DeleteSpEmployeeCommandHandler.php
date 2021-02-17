<?php

namespace CachetHQ\Cachet\Bus\Handlers\Commands\SpEmployees;

use CachetHQ\Cachet\Bus\Commands\SpEmployees\DeleteSpEmployeeCommand;

/**
 * This is the unsubscribe subscriber command class.
 *
 * @author Joseph Cohem <joe@alt-three.com>
 * @author Graham Campbell <graham@alt-three.com>
 * @author James Brooks <james@alt-three.com>
 */
class DeleteSpEmployeeCommandHandler
{
    /**
     * Handle the delete employee command.
     *
     * @param \CachetHQ\Cachet\Bus\Commands\SpEmployees\DeleteSpEmployeeCommand $command
     *
     * @return void
     */
    public function handle(DeleteSpEmployeeCommand $command)
    {
        $employee = $command->employee;

        // First remove subscriptions.
        $employee->allowedGroups()->delete();

        // Then remove the subscriber.
        $employee->delete();
    }
}
