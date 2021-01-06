<?php
namespace CachetHQ\Cachet\Bus\Handlers\Commands\UserGroups;

use CachetHQ\Cachet\Bus\Commands\Subscriber\SubscribeSubscriberCommand;
use CachetHQ\Cachet\Bus\Commands\Subscriber\VerifySubscriberCommand;
use CachetHQ\Cachet\Bus\Commands\UserGroups\UpdateUserGroupCommand;
use CachetHQ\Cachet\Bus\Events\Subscriber\SubscriberHasSubscribedEvent;
use CachetHQ\Cachet\Models\Component;
use CachetHQ\Cachet\Models\Subscriber;
use CachetHQ\Cachet\Models\Subscription;
use CachetHQ\Cachet\Models\User;
use CachetHQ\Cachet\Models\UserGroup;
use CachetHQ\Cachet\Notifications\Subscriber\VerifySubscriptionNotification;

/**
 * This is the subscribe subscriber command handler.
 *
 * @author James Brooks <james@alt-three.com>
 * @author Joseph Cohen <joe@alt-three.com>
 * @author Graham Campbell <graham@alt-three.com>
 */
class UpdateUserGroupCommandHandler
{
    /**
     * Handle the subscribe subscriber command.
     *
     * @param \CachetHQ\Cachet\Bus\Commands\UserGroups\UpdateUserGroupCommand $command
     *
     * @return \CachetHQ\Cachet\Models\UserGroup
     */
    public function handle(UpdateUserGroupCommand $command)
    {
        $userGroup = $command->userGroup;
        $name = $command->name;

        $userGroup->name = $name;
        $userGroup->save();

        return $userGroup;
    }
}
