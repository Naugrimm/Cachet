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

            <div class="row">
                <div class="col-sm-12">
                    <form name="searchForm" role="form" method="GET" class="form-vertical">
                        <fieldset><label for="search">Name</label>
                            <input type="text" name="search" id="search" value="" class="form-control">
                            <br>
                        </fieldset>
                        <div class="form-group">
                            <div class="btn-group">
                                <button type="submit" class="btn btn-success">suchen</button>
                            </div>
                        </div>
                    </form>
                </div>
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
            <nav>
                <ul class="pager">
                    @if(!$employees->onFirstPage())
                        <li class="previous">
                            <a href="{{ $employees->previousPageUrl() }}" class="links">
                                <span aria-hidden="true">&larr;</span> {{ trans('pagination.previous') }}
                            </a>
                        </li>
                    @endif
                    @if($employees->hasMorePages())
                        <li class="next">
                            <a href="{{ $employees->nextPageUrl() }}" class="links">
                                {{ trans('pagination.next') }} <span aria-hidden="true">&rarr;</span>
                            </a>
                        </li>
                    @endif
                </ul>
            </nav>
        </div>
    </div>
@stop
