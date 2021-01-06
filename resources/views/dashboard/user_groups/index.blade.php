@extends('layout.dashboard')

@section('content')
    <div class="header fixed">
        <div class="sidebar-toggler visible-xs">
            <i class="ion ion-navicon"></i>
        </div>
        <span class="uppercase">
        <i class="ion ion-ios-people-outline"></i> {{ trans('dashboard.user_groups.user_groups') }}
    </span>
        @if($currentUser->isAdmin)
            <a class="btn btn-md btn-success pull-right" href="{{ cachet_route('dashboard.user_groups.create') }}">
                {{ trans('dashboard.user_groups.add.user_group') }}
            </a>
        @endif
        <div class="clearfix"></div>
    </div>
    <div class="content-wrapper header-fixed">
        <div class="row">
            <div class="col-sm-12">
                <p class="lead">
                    {{ trans('dashboard.user_groups.description') }}
                </p>

                <div class="striped-list">
                    @foreach($groups as $group)
                        <div class="row striped-list-item">
                            <div class="col-xs-6">
                                <p>{{ $group->name }}</p>
                            </div>
                            <div class="col-xs-6 text-right">
                                <a href="{{ cachet_route('dashboard.user_groups.edit', ['userGroup' => $group->id]) }}" target="_blank" class="btn btn-success">{{ trans('forms.edit') }}</a>
                                <a href="{{ cachet_route('dashboard.user_groups.delete', [$group->id], 'delete') }}" class="btn btn-danger confirm-action" data-method='DELETE'>{{ trans('forms.delete') }}</a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@stop
