<?php

namespace CachetHQ\Cachet\Http\Routes\Dashboard;

use Illuminate\Contracts\Routing\Registrar;

/**
 * This is the dashboard subscriber routes class.
 *
 * @author James Brooks <james@alt-three.com>
 * @author Connor S. Parks <connor@connorvg.tv>
 */
class UserGroupRoutes
{
    /**
     * Defines if these routes are for the browser.
     *
     * @var bool
     */
    public static $browser = true;

    /**
     * Define the dashboard subscriber routes.
     *
     * @param \Illuminate\Contracts\Routing\Registrar $router
     *
     * @return void
     */
    public function map(Registrar $router)
    {
        $router->group([
            'middleware' => ['auth'],
            'namespace'  => 'Dashboard',
            'prefix'     => 'dashboard/user_groups',
        ], function (Registrar $router) {
            $router->get('/', [
                'as'   => 'get:dashboard.user_groups',
                'uses' => 'UserGroupController@showUserGroups',
            ]);

            $router->get('create', [
                'as'   => 'get:dashboard.user_groups.create',
                'uses' => 'UserGroupController@showAddUserGroup',
            ]);

            $router->post('create', [
                'as'   => 'post:dashboard.user_groups.create',
                'uses' => 'UserGroupController@createUserGroup',
            ]);

            $router->get('{userGroup}', [
                'as'   => 'get:dashboard.user_groups.edit',
                'uses' => 'UserGroupController@showUpdateUserGroup',
            ]);

            $router->post('{userGroup}', [
                'as'   => 'post:dashboard.user_groups.edit',
                'uses' => 'UserGroupController@updateUserGroup',
            ]);

            $router->delete('{userGroup}/delete', [
                'as'   => 'delete:dashboard.user_groups.delete',
                'uses' => 'UserGroupController@deleteUserGroup',
            ]);

        });
    }
}
