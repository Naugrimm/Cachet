<?php

namespace CachetHQ\Cachet\Bus\Handlers\Commands\UserGroups;

use CachetHQ\Cachet\Bus\Commands\Subscriber\SubscribeSubscriberCommand;
use CachetHQ\Cachet\Bus\Commands\Subscriber\VerifySubscriberCommand;
use CachetHQ\Cachet\Bus\Commands\UserGroups\AddUserGroupCommand;
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
class AddUserGroupCommandHandler
{
    /**
     * Handle the subscribe subscriber command.
     *
     * @param \CachetHQ\Cachet\Bus\Commands\UserGroups\AddUserGroupCommand $command
     *
     * @return \CachetHQ\Cachet\Models\UserGroup
     */
    public function handle(AddUserGroupCommand $command)
    {
        if ($subscriber = UserGroup::where('name', '=', $command->name)->first()) {
            return $subscriber;
        }

        $subscriber = UserGroup::firstOrCreate(['name' => $command->name]);

        return $subscriber;
    }
}
