<?php

/*
 * This file is part of Cachet.
 *
 * (c) Alt Three Services Limited
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CachetHQ\Cachet\Http\Controllers;

use AltThree\Badger\Facades\Badger;
use CachetHQ\Cachet\Http\Controllers\Api\AbstractApiController;
use CachetHQ\Cachet\Models\AllowedGroups;
use CachetHQ\Cachet\Models\Component;
use CachetHQ\Cachet\Models\Incident;
use CachetHQ\Cachet\Models\Metric;
use CachetHQ\Cachet\Models\Schedule;
use CachetHQ\Cachet\Models\SpEmployees;
use CachetHQ\Cachet\Repositories\Metric\MetricRepository;
use CachetHQ\Cachet\Services\Dates\DateFactory;
use GrahamCampbell\Binput\Facades\Binput;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Str;
use Jenssegers\Date\Date;
use McCool\LaravelAutoPresenter\Facades\AutoPresenter;

/**
 * This is the status page controller class.
 *
 * @author James Brooks <james@alt-three.com>
 * @author Graham Campbell <graham@alt-three.com>
 * @author Joseph Cohen <joe@alt-three.com>
 */
class StatusPageController extends AbstractApiController
{
    /**
     * Displays the status page.
     *
     */
    public function showIndex()
    {
        $onlyDisruptedDays = Config::get('setting.only_disrupted_days');
        $appIncidentDays = (int) Config::get('setting.app_incident_days', 1);

        $canPageForward = false;
        $canPageBackward = false;
        $previousDate = null;
        $nextDate = null;

        if ($onlyDisruptedDays) {
            $input = [
                'start_date' => Binput::get('start_date', 0),
            ];
            $validator = Validator::make($input, [
                'start_date' => 'sometimes|required|integer',
            ]);
            if($validator->fails()) {
                return redirect(Route::current()->uri);
            }

            // In this case, start_date GET parameter means the page
            $page = (int) Binput::get('start_date', 0);

            $allIncidentDays = Incident::where('visible', '>=', (int) !Auth::check())
                                       ->select('occurred_at')
                                       ->distinct()
                                       ->orderBy('occurred_at', 'desc')
                                       ->get()
                                       ->map(function (Incident $incident) {
                                           return app(DateFactory::class)->make($incident->occurred_at)->toDateString();
                                       })->unique()
                                      ->values();

            $numIncidentDays = count($allIncidentDays);
            $numPages = ceil($numIncidentDays / max($appIncidentDays, 1));

            $selectedDays = $allIncidentDays->slice($page * $appIncidentDays, $appIncidentDays)->all();

            if (count($selectedDays) > 0) {
                $startDate = Date::createFromFormat('Y-m-d', array_values($selectedDays)[0]);
                $endDate = Date::createFromFormat('Y-m-d', array_values(array_slice($selectedDays, -1))[0]);
            }

            $canPageForward = $page > 0;
            $canPageBackward = ($page + 1) < $numPages;
            $previousDate = $page + 1;
            $nextDate = $page - 1;
        } else {
            $input = [
                'start_date' => Binput::get('start_date', Date::now()->format('Y-m-d')),
            ];
            $validator = Validator::make($input, [
                'start_date' => 'sometimes|required|date',
            ]);
            if($validator->fails()) {
                return redirect(Route::current()->uri);
            }

            $startDate = Date::createFromFormat('Y-m-d', Binput::get('start_date', Date::now()->toDateString()));
            $endDate = $startDate->copy()->subDays($appIncidentDays - 1);

            $date = Date::now();

            $canPageForward = (bool) $startDate->lt($date->sub('1 day'));
            $canPageBackward = Incident::where('occurred_at', '<', $date->format('Y-m-d'))->count() > 0;
            $previousDate = $startDate->copy()->subDays($appIncidentDays)->toDateString();
            $nextDate = $startDate->copy()->addDays($appIncidentDays)->toDateString();
        }

        if(Auth::user()) {
            $allIncidents = Incident::with('component', 'updates.incident')
                ->where('visible', '>=', (int)!Auth::check())->whereBetween('occurred_at', [
                    $endDate->format('Y-m-d') . ' 00:00:00',
                    $startDate->format('Y-m-d') . ' 23:59:59',
                ])->orderBy('occurred_at', 'desc')->get()->groupBy(function (Incident $incident) {
                    return app(DateFactory::class)->make($incident->occurred_at)->toDateString();
                });
        }elseif(session()->exists('sp_employee')) {
            $userGroupIds = SpEmployees::find(session()->get('sp_employee'))->allowedGroups()->select('user_groups_id')->get()->pluck('user_groups_id');

            $allIncidents = Incident::with('component', 'updates.incident')
                ->where('visible', '>=', (int)!Auth::check())
                ->where(function ($query) use ($userGroupIds) {
                    $query->where('user_groups_id', '=', 0)
                        ->orWhereIn('user_groups_id', $userGroupIds);
                })
                ->whereBetween('occurred_at', [
                    $endDate->format('Y-m-d') . ' 00:00:00',
                    $startDate->format('Y-m-d') . ' 23:59:59',
                ])->orderBy('occurred_at', 'desc')->get()->groupBy(function (Incident $incident) {
                    return app(DateFactory::class)->make($incident->occurred_at)->toDateString();
                });
        }else {
            $allIncidents = Incident::with('component', 'updates.incident')
                ->where('visible', '>=', (int)!Auth::check())
                ->where('user_groups_id', '=', 0)
                ->whereBetween('occurred_at', [
                    $endDate->format('Y-m-d') . ' 00:00:00',
                    $startDate->format('Y-m-d') . ' 23:59:59',
                ])->orderBy('occurred_at', 'desc')->get()->groupBy(function (Incident $incident) {
                    return app(DateFactory::class)->make($incident->occurred_at)->toDateString();
                });
        }

        if (!$onlyDisruptedDays) {
            $incidentDays = array_pad([], $appIncidentDays, null);

            // Add in days that have no incidents
            foreach ($incidentDays as $i => $day) {
                $date = app(DateFactory::class)->make($startDate)->subDays($i);

                if (!isset($allIncidents[$date->toDateString()])) {
                    $allIncidents[$date->toDateString()] = [];
                }
            }
        }

        // Sort the array so it takes into account the added days
        $allIncidents = $allIncidents->sortBy(function ($value, $key) {
            return strtotime($key);
        }, SORT_REGULAR, true);

        return View::make('index')
            ->withDaysToShow($appIncidentDays)
            ->withAllIncidents($allIncidents)
            ->withCanPageForward($canPageForward)
            ->withCanPageBackward($canPageBackward)
            ->withPreviousDate($previousDate)
            ->withNextDate($nextDate);
    }

    /**
     * Shows an incident in more detail.
     *
     * @param \CachetHQ\Cachet\Models\Incident $incident
     *
     * @return \Illuminate\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|\Illuminate\View\View
     */
    public function showIncident(Incident $incident)
    {
        if($incident->user_groups_id != 0) {
            if(Auth::user()) {
                return View::make('single-incident')->withIncident($incident);
            }elseif(session()->exists('sp_employee')) {
                $count = AllowedGroups::where('sp_employees_id', '=', session()->get('sp_employee'))
                ->where('user_groups_id', '=', $incident->user_groups_id)->get()->count();

                if($count > 0) {
                    return View::make('single-incident')->withIncident($incident);
                } else {
                    return redirect(cachet_route('status-page'));
                }

            }else {
                return redirect(cachet_route('status-page'));
            }
        }

        return View::make('single-incident')->withIncident($incident);
    }

    /**
     * Show a single schedule.
     *
     * @param \CachetHQ\Cachet\Models\Schedule $schedule
     *
     * @return \Illuminate\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|\Illuminate\View\View
     */
    public function showSchedule(Schedule $schedule)
    {
        if($schedule->user_groups_id != 0) {
            if(Auth::user()) {
                return View::make('single-schedule')->withSchedule($schedule);
            }elseif(session()->exists('sp_employee')) {
                $count = AllowedGroups::where('sp_employees_id', '=', session()->get('sp_employee'))
                    ->where('user_groups_id', '=', $schedule->user_groups_id)->get()->count();
                if($count > 0) {
                    return View::make('single-schedule')->withSchedule($schedule);
                } else {
                    return redirect(cachet_route('status-page'));
                }

            }else {
                return redirect(cachet_route('status-page'));
            }
        }

        return View::make('single-schedule')->withSchedule($schedule);
    }

    /**
     * Returns metrics in a readily formatted way.
     *
     * @param \CachetHQ\Cachet\Models\Metric $metric
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getMetrics(Metric $metric)
    {
        $type = Binput::get('filter', AutoPresenter::decorate($metric)->view_name);
        $metrics = app(MetricRepository::class);

        switch ($type) {
            case 'last_hour': $metricData = $metrics->listPointsLastHour($metric); break;
            case 'today': $metricData = $metrics->listPointsToday($metric); break;
            case 'week': $metricData = $metrics->listPointsForWeek($metric); break;
            case 'month': $metricData = $metrics->listPointsForMonth($metric); break;
            default: $metricData = [];
        }

        return $this->item([
            'metric' => $metric->toArray(),
            'items'  => $metricData,
        ]);
    }

    /**
     * Generates a Shield (badge) for the component.
     *
     * @param \CachetHQ\Cachet\Models\Component $component
     *
     * @return \Illuminate\Http\Response
     */
    public function showComponentBadge(Component $component)
    {
        $component = AutoPresenter::decorate($component);

        switch ($component->status_color) {
            case 'reds': $color = Config::get('setting.style_reds', '#FF6F6F'); break;
            case 'blues': $color = Config::get('setting.style_blues', '#3498DB'); break;
            case 'greens': $color = Config::get('setting.style_greens', '#7ED321'); break;
            case 'yellows': $color = Config::get('setting.style_yellows', '#F7CA18'); break;
            default: $color = null;
        }

        $badge = Badger::generate(
            $component->name,
            $component->human_status,
            substr($color, 1),
            Binput::get('style', 'flat-square')
        );

        return Response::make($badge, 200, ['Content-Type' => 'image/svg+xml']);
    }


    /**
     * Show the privacy statement.
     *
     * @return \Illuminate\Routing\Redirector|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function showPrivacyStatement()
    {
        $privacyStatement = trim(Config::get('setting.privacy_statement', ''));
        if (!$privacyStatement) {
            return abort(404);
        }
        if (Str::startsWith($privacyStatement, ['http://', 'https://']) && filter_var($privacyStatement, FILTER_VALIDATE_URL)) {
            return redirect($privacyStatement);
        }

        return View::make('privacy')
            ->withPrivacyStatement($privacyStatement);
    }

    /**
     * Show the imprint.
     *
     * @return \Illuminate\Routing\Redirector|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function showImprint()
    {
        $imprint = trim(Config::get('setting.imprint', ''));
        if (!$imprint) {
            return abort(404);
        }
        if (Str::startsWith($imprint, ['http://', 'https://']) && filter_var($imprint, FILTER_VALIDATE_URL)) {
            return redirect($imprint);
        }

        return View::make('imprint')
            ->withImprint($imprint);
    }

    public function showAllSchedules()
    {
        $onlyDisruptedDays = Config::get('setting.only_schedule_days');
        $appScheduleDays = (int) Config::get('setting.app_incident_days', 1);

        if ($onlyDisruptedDays) {
            $input = [
                'start_date' => Binput::get('start_date', 0),
            ];
            $validator = Validator::make($input, [
                'start_date' => 'sometimes|required|integer',
            ]);
            if($validator->fails()) {
                return redirect(Route::current()->uri);
            }

            // In this case, start_date GET parameter means the page
            $page = (int) Binput::get('start_date', 0);

            if(Auth::user()) {
                $schedule = Schedule::query();
            }elseif(session()->exists('sp_employee')) {
                $userGroupIds = SpEmployees::find(session()->get('sp_employee'))->allowedGroups()->select('user_groups_id')->get()->pluck('user_groups_id');

                $schedule = Schedule::where('user_groups_id', '=', 0)
                    ->orWhereIn('user_groups_id', $userGroupIds);
            } else {
                $schedule = Schedule::where('user_groups_id', '=', 0);
            }

            $allScheduleDays = $schedule->
                select('scheduled_at')
                ->distinct()
                ->orderBy('scheduled_at', 'desc')
                ->get()
                ->map(function (Schedule $schedule) {
                    return app(DateFactory::class)->make($schedule->scheduled_at)->toDateString();
                })->unique()
                ->values();

            $numScheduleDays = count($allScheduleDays);
            $numPages = ceil($numScheduleDays / max($appScheduleDays, 1));

            $selectedDays = $allScheduleDays->slice($page * $appScheduleDays, $appScheduleDays)->all();

            if (count($selectedDays) > 0) {
                $startDate = Date::createFromFormat('Y-m-d', array_values($selectedDays)[0]);
                $endDate = Date::createFromFormat('Y-m-d', array_values(array_slice($selectedDays, -1))[0]);
            }

            $canPageForward = $page > 0;
            $canPageBackward = ($page + 1) < $numPages;
            $previousDate = $page + 1;
            $nextDate = $page - 1;
        } else {
            if(Auth::user()) {
                $schedule = Schedule::query();
            }elseif(session()->exists('sp_employee')) {
                $userGroupIds = SpEmployees::find(session()->get('sp_employee'))->allowedGroups()->select('user_groups_id')->get()->pluck('user_groups_id');

                $schedule = Schedule::where('user_groups_id', '=', 0)
                    ->orWhereIn('user_groups_id', $userGroupIds);
            } else {
                $schedule = Schedule::where('user_groups_id', '=', 0);
            }

            $biggestDate = $schedule->orderBy('scheduled_at', 'desc')->first();
            if(!$biggestDate) {
                $biggestDate = Date::now();
            } else {
                $biggestDate = $biggestDate->scheduled_at->endOfDay();
            }

            $input = [
                'start_date' => Binput::get('start_date', $biggestDate->format('Y-m-d')),
            ];
            $validator = Validator::make($input, [
                'start_date' => 'sometimes|required|date',
            ]);
            if($validator->fails()) {
                return redirect(Route::current()->uri);
            }

            $startDate = Date::createFromFormat('Y-m-d', Binput::get('start_date', $biggestDate->toDateString()));
            $endDate = $startDate->copy()->subDays($appScheduleDays - 1);

            $date = Date::now();

            $canPageForward = (bool) $startDate->lt( Date::parse($biggestDate)->sub('1 day'));
            $canPageBackward = Schedule::where('scheduled_at', '<', $date->format('Y-m-d'))->count() > 0;
            $previousDate = $startDate->copy()->subDays($appScheduleDays)->toDateString();
            $nextDate = $startDate->copy()->addDays($appScheduleDays)->toDateString();
        }

        $allSchedules = Schedule::
            whereBetween('scheduled_at', [
                $endDate->format('Y-m-d').' 00:00:00',
                $startDate->format('Y-m-d').' 23:59:59',
            ])->orderBy('scheduled_at', 'desc')->get()->groupBy(function (Schedule $schedule) {
                return app(DateFactory::class)->make($schedule->scheduled_at)->toDateString();
            });


        if (!$onlyDisruptedDays) {
            $scheduleDays = array_pad([], $appScheduleDays, null);

            // Add in days that have no incidents
            foreach ($scheduleDays as $i => $day) {
                $date = app(DateFactory::class)->make($startDate)->subDays($i);

                if (!isset($allSchedules[$date->toDateString()])) {
                    $allSchedules[$date->toDateString()] = [];
                }
            }
        }

        // Sort the array so it takes into account the added days
        $allSchedules = $allSchedules->sortBy(function ($value, $key) {
            return strtotime($key);
        }, SORT_REGULAR, true);

        return View::make('schedules')
            ->withAllSchedules($allSchedules)
            ->withCanPageForward($canPageForward)
            ->withCanPageBackward($canPageBackward)
            ->withPreviousDate($previousDate)
            ->withNextDate($nextDate);
    }
}
