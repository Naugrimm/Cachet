<?php

/*
 * This file is part of Cachet.
 *
 * (c) Alt Three Services Limited
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CachetHQ\Cachet\Bus\Handlers\Commands\Subscriber;

use CachetHQ\Cachet\Bus\Commands\Subscriber\UpdateSubscriberSubscriptionCommand;
use CachetHQ\Cachet\Bus\Events\Subscriber\SubscriberHasUpdatedSubscriptionsEvent;
use CachetHQ\Cachet\Models\AllowedGroups;
use CachetHQ\Cachet\Models\Component;
use CachetHQ\Cachet\Models\SpEmployees;
use CachetHQ\Cachet\Models\Subscriber;
use CachetHQ\Cachet\Models\UserGroup;

/**
 * This is the subscribe subscriber command handler.
 *
 * @author Joseph Cohen <joe@alt-three.com>
 */
class UpdateSubscriberSubscriptionCommandHandler
{
    /**
     * Handle the subscribe subscriber command.
     *
     * @param \CachetHQ\Cachet\Bus\Commands\Subscriber\UpdateSubscriberSubscriptionCommand $command
     *
     * @return \CachetHQ\Cachet\Models\Subscriber
     */
    public function handle(UpdateSubscriberSubscriptionCommand $command)
    {
        $subscriber = $command->subscriber;
        $allowedGroups = $command->allowedGroups ?: [];

        $userGroups = UserGroup::get();

        $updateAllowedGroups = $userGroups->filter(function ($item) use ($allowedGroups) {
            return in_array($item->id, $allowedGroups);
        });

//dd($subscriber);
        $subscriber->allowedGroups()->delete();

        if (!$updateAllowedGroups->isEmpty()) {
            $updateAllowedGroups->each(function ($allowedGroup) use ($subscriber) {
                if($subscriber instanceof Subscriber) {
                    AllowedGroups::firstOrCreate([
                        'users_id' => $subscriber->id,
                        'user_groups_id' => $allowedGroup->id,
                    ]);
                }elseif($subscriber instanceof SpEmployees) {
                    AllowedGroups::firstOrCreate([
                        'sp_employees_id' => $subscriber->id,
                        'user_groups_id' => $allowedGroup->id,
                    ]);
                }
            });
        }

        $subscriber->save();

        event(new SubscriberHasUpdatedSubscriptionsEvent($subscriber));

        return $subscriber;
    }
}
