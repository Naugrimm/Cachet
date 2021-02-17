<?php

namespace CachetHQ\Cachet\Http\Routes\Dashboard;

use Illuminate\Contracts\Routing\Registrar;

class EmployeeRoutes
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
            'prefix'     => 'dashboard/employees',
        ], function (Registrar $router) {
            $router->get('/', [
                'as'   => 'get:dashboard.employees',
                'uses' => 'EmployeesController@showEmployees',
            ]);

            $router->get('{employee}/editUserGroups', [
                'as'   => 'get:dashboard.employees.edit',
                'uses' => 'EmployeesController@showEditUserGroups',
            ]);

            $router->post('{employee}/editUserGroups', [
                'as'   => 'post:dashboard.employees.edit',
                'uses' => 'EmployeesController@updateUserGroups',
            ]);

            $router->delete('{employee}/delete', [
                'as'   => 'delete:dashboard.employees.delete',
                'uses' => 'EmployeesController@deleteEmployeeAction',
            ]);
        });
    }
}
