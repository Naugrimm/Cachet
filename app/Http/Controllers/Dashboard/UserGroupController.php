<?php

namespace CachetHQ\Cachet\Http\Controllers\Dashboard;

use AltThree\Validator\ValidationException;
use CachetHQ\Cachet\Bus\Commands\UserGroups\AddUserGroupCommand;
use CachetHQ\Cachet\Bus\Commands\Subscriber\UnsubscribeSubscriberCommand;
use CachetHQ\Cachet\Bus\Commands\UserGroups\DeleteUserGroupCommand;
use CachetHQ\Cachet\Bus\Commands\UserGroups\UpdateUserGroupCommand;
use CachetHQ\Cachet\Models\Subscriber;
use GrahamCampbell\Binput\Facades\Binput;
use Illuminate\Contracts\Config\Repository;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\View;
use CachetHQ\Cachet\Models\UserGroup;

class UserGroupController extends Controller
{
    /**
     * Shows the subscribers view.
     *
     * @return \Illuminate\View\View
     */
    public function showUserGroups()
    {
        return View::make('dashboard.user_groups.index')
            ->withPageTitle(trans('dashboard.user_groups.user_groups').' - '.trans('dashboard.dashboard'))
            ->withGroups(UserGroup::all());
    }

    /**
     * Shows the add subscriber view.
     *
     * @return \Illuminate\View\View
     */
    public function showAddUserGroup()
    {
        return View::make('dashboard.user_groups.add')
            ->withPageTitle(trans('dashboard.user_groups.add.title').' - '.trans('dashboard.dashboard'));
    }

    /**
     * Creates a new subscriber.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function createUserGroup()
    {

        try {
            $userGroupName = Binput::get('name');
            execute(new AddUserGroupCommand($userGroupName));
        } catch (ValidationException $e) {
            return cachet_redirect('dashboard.user_groups.create')
                ->withInput(Binput::all())
                ->withTitle(sprintf('%s %s', trans('dashboard.notifications.whoops'), trans('dashboard.user_groups.add.failure')))
                ->withErrors($e->getMessageBag());
        }

        return cachet_redirect('dashboard.user_groups.create')
            ->withSuccess(sprintf('%s %s', trans('dashboard.notifications.awesome'), trans('dashboard.user_groups.add.success')));
    }

    /**
     * @param UserGroup $userGroup
     * @return mixed
     */
    public function showUpdateUserGroup(UserGroup $userGroup) {

        $pageTitle = sprintf('"%s" - %s - %s', $userGroup->name, trans('dashboard.user_groups.edit.title'), trans('dashboard.dashboard'));

        return View::make('dashboard.user_groups.edit')
            ->withPageTitle($pageTitle)
            ->withUserGroup($userGroup);
    }

    /**
     * @param UserGroup $userGroup
     * @return mixed
     */
    public function updateUserGroup(UserGroup $userGroup) {
        $userGroupData = Binput::get('user_group');

        try {
            $component = execute(new UpdateUserGroupCommand(
                $userGroup,
                $userGroupData['name']
            ));
        } catch (ValidationException $e) {
            return cachet_redirect('dashboard.user_groups.edit', [$userGroup->id])
                ->withInput(Binput::all())
                ->withTitle(sprintf('%s %s', trans('dashboard.notifications.whoops'), trans('dashboard.user_groups.edit.failure')))
                ->withErrors($e->getMessageBag());
        }

        return cachet_redirect('dashboard.user_groups.edit', [$component->id])
            ->withSuccess(sprintf('%s %s', trans('dashboard.notifications.awesome'), trans('dashboard.user_groups.edit.success')));
    }

    public function deleteUserGroup(UserGroup $userGroup) {
        try {
            $component = execute(new DeleteUserGroupCommand(
                $userGroup
            ));
        } catch (ValidationException $e) {
            return cachet_redirect('dashboard.user_groups.edit', [$userGroup->id])
                ->withInput(Binput::all())
                ->withTitle(sprintf('%s %s', trans('dashboard.notifications.whoops'), trans('dashboard.user_groups.delete.failure')))
                ->withErrors($e->getMessageBag());
        }

        return cachet_redirect('dashboard.user_groups')
            ->withSuccess(sprintf('%s %s', trans('dashboard.notifications.awesome'), trans('dashboard.user_groups.delete.success')));
    }

    /**
     * Deletes a subscriber.
     *
     * @param \CachetHQ\Cachet\Models\Subscriber $subscriber
     *
     * @throws \Exception
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function deleteSubscriberAction(Subscriber $subscriber)
    {
        execute(new UnsubscribeSubscriberCommand($subscriber));

        return cachet_redirect('dashboard.subscribers');
    }
}
