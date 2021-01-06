@extends('layout.dashboard')

@section('content')
    <div class="header">
        <div class="sidebar-toggler visible-xs">
            <i class="ion ion-navicon"></i>
        </div>
        <span class="uppercase">
        <i class="ion ion-ios-people-outline"></i> {{ trans('dashboard.user_groups.user_groups') }}
    </span>
        &gt; <small>{{ trans('dashboard.user_groups.edit.title') }}</small>
    </div>
    <div class="content-wrapper">
        <div class="row">
            <div class="col-sm-12">
                @include('partials.errors')
                <form name="EditComponentForm" class="form-vertical" role="form" action="{{ cachet_route('dashboard.user_groups.edit', [$userGroup->id], 'post') }}" method="POST">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <fieldset>
                        <div class="form-group">
                            <label for="incident-name">{{ trans('forms.components.name') }}</label>
                            <input type="text" class="form-control" name="user_group[name]" id="user_group-name" required value="{{ $userGroup->name }}">
                        </div>
                    </fieldset>

                    <div class="btn-group">
                        <button type="submit" class="btn btn-success">{{ trans('forms.save') }}</button>
                        <a class="btn btn-default" href="{{ cachet_route('dashboard.user_groups') }}">{{ trans('forms.cancel') }}</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@stop
