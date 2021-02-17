@extends('layout.dashboard')

@section('content')
    <div class="header fixed">
        <div class="sidebar-toggler visible-xs">
            <i class="ion ion-navicon"></i>
        </div>
        <span class="uppercase">
        <i class="ion ion-ios-people-outline"></i> {{ trans('dashboard.employees.employees') }}
    </span>
        <div class="clearfix"></div>
    </div>
    <div class="content-wrapper header-fixed">
        <div class="row">
            <div class="col-sm-12">
                <p class="lead">
                    {{ trans('dashboard.employees.description') }}
                </p>
            </div>

            <div class="striped-list">
                @foreach($employees as $employee)
                    <div class="row striped-list-item">
                        <div class="col-xs-4">
                            <p>{{ $employee->firstname }} {{ $employee->lastname }}</p>
                        </div>
                        <div class="col-xs-4">
                            @if($employee->allowedGroups->isNotEmpty())
                                {!! $employee->allowedGroups->map(function ($allowedGroup) {
                                    return sprintf('<span class="label label-primary">%s</span>', $allowedGroup->group->name);
                                })->implode(' ') !!}
                            @else
                                <p>{{ trans('dashboard.subscribers.no_subscriptions') }}</p>
                            @endif
                        </div>
                        <div class="col-xs-4 text-right">
                            <a href="{{ cachet_route('dashboard.employees.edit', ['userGroup' => $employee->id]) }}" target="_blank" class="btn btn-success">{{ trans('forms.edit') }}</a>
                            <a href="{{ cachet_route('dashboard.employees.delete', [$employee->id], 'delete') }}" class="btn btn-danger confirm-action" data-method='DELETE'>{{ trans('forms.delete') }}</a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@stop
