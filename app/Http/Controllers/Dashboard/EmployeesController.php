<?php

namespace CachetHQ\Cachet\Http\Controllers\Dashboard;

use AltThree\Validator\ValidationException;
use CachetHQ\Cachet\Bus\Commands\SpEmployees\DeleteSpEmployeeCommand;
use CachetHQ\Cachet\Bus\Commands\Subscriber\UpdateSubscriberSubscriptionCommand;
use CachetHQ\Cachet\Models\SpEmployees;
use CachetHQ\Cachet\Models\Subscriber;
use CachetHQ\Cachet\Models\UserGroup;
use GrahamCampbell\Binput\Facades\Binput;
use Illuminate\Contracts\Config\Repository;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class EmployeesController extends Controller
{
    /**
     * @param Request $request
     * Shows the subscribers view.
     *
     * @return \Illuminate\View\View
     */
    public function showEmployees(Request $request)
    {
        if($request->input('search')) {
            $search = $request->input('search');
            $employees = SpEmployees::where('username', 'LIKE', '%'.$search.'%')
                ->orWhere('firstname', 'LIKE', '%'.$search.'%')
                ->orWhere('lastname', 'LIKE', '%'.$search.'%')
                ->paginate(10)->appends(['search' => $search]);
        } else {
            $employees = SpEmployees::paginate(10);
        }

        return View::make('dashboard.employees.index')
            ->withPageTitle(trans('dashboard.employees.employees').' - '.trans('dashboard.dashboard'))
            ->withEmployees($employees);
    }

    /**
     * @param SpEmployees $employee
     * @return mixed
     */
    public function showEditUserGroups(SpEmployees $employee)
    {
        $userGroups = UserGroup::all()->paginate(15);

        if (!$employee) {
            throw new BadRequestHttpException();
        }

        return View::make('dashboard.employees.edit')
            ->withEmployee($employee)
            ->withSubscriptions($employee->allowedGroups->pluck('user_groups_id')->all())
            ->withUserGroups($userGroups);
    }

    public function updateUserGroups(SpEmployees $employee)
    {
        if (!$employee) {
            throw new BadRequestHttpException();
        }

        try {
            execute(new UpdateSubscriberSubscriptionCommand($employee, Binput::get('subscriptions')));
        } catch (ValidationException $e) {
            return redirect()->to(cachet_route('dashboard.employees.edit', ['userGroup' => $employee->id]))
                ->withInput(Binput::all())
                ->withTitle(sprintf('%s %s', trans('dashboard.notifications.whoops'), trans('dashboard.employees.manage.failure')))
                ->withErrors($e->getMessageBag());
        }

        return redirect()->to(cachet_route('dashboard.employees.edit', ['userGroup' => $employee->id]))
            ->withSuccess(sprintf('%s %s', trans('dashboard.notifications.awesome'), trans('dashboard.employees.manage.success')));
    }

    /**
     * Deletes an employee.
     *
     * @param \CachetHQ\Cachet\Models\SpEmployees $employee
     *
     * @throws \Exception
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function deleteEmployeeAction(SpEmployees $employee)
    {
        execute(new DeleteSpEmployeeCommand($employee));

        return cachet_redirect('dashboard.employees');
    }
}
