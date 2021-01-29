<?php
namespace CachetHQ\Cachet\Bus\Handlers\Commands\UserGroup;

use CachetHQ\Cachet\Bus\Commands\Subscriber\SubscribeSubscriberCommand;
use CachetHQ\Cachet\Bus\Commands\Subscriber\VerifySubscriberCommand;
use CachetHQ\Cachet\Bus\Commands\UserGroup\DeleteUserGroupCommand;
use CachetHQ\Cachet\Bus\Events\Subscriber\SubscriberHasSubscribedEvent;
use CachetHQ\Cachet\Models\AllowedGroups;
use CachetHQ\Cachet\Models\Component;
use CachetHQ\Cachet\Models\ComponentGroup;
use CachetHQ\Cachet\Models\Incident;
use CachetHQ\Cachet\Models\Schedule;
use CachetHQ\Cachet\Models\Subscriber;
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
class DeleteUserGroupCommandHandler
{
    /**
     * @param DeleteUserGroupCommand $command
     * @throws \Exception
     */
    public function handle(DeleteUserGroupCommand $command)
    {
        $userGroup = $command->userGroup;

        $allowedGroups = AllowedGroups::where('user_groups_id', '=', $userGroup->id)->get();
        foreach($allowedGroups as $allowedGroup) {
            $allowedGroup->delete();
        }

        $componentsGroups = ComponentGroup::where('user_groups_id', '=', $userGroup->id)->get();
        foreach($componentsGroups as $componentsGroup) {
            $componentsGroup->user_groups_id = 0;
            $componentsGroup->save();
        }

        $components = Component::where('user_groups_id', '=', $userGroup->id)->get();
        foreach($components as $component) {
            $component->user_groups_id = 0;
            $component->save();
        }

        $incidents = Incident::where('user_groups_id', '=', $userGroup->id)->get();
        foreach($incidents as $incident) {
            $incident->user_groups_id = 0;
            $incident->save();
        }

        $schedules = Schedule::where('user_groups_id', '=', $userGroup->id)->get();
        foreach($schedules as $schedule) {
            $schedule->user_groups_id = 0;
            $schedule->save();
        }


        $userGroup->delete();
    }
}
