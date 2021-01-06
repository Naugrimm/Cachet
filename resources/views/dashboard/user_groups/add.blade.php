@extends('layout.dashboard')

@section('content')
    <div class="header">
        <div class="sidebar-toggler visible-xs">
            <i class="ion ion-navicon"></i>
        </div>
        <span class="uppercase">
        <i class="ion ion-ios-people-outline"></i> {{ trans('dashboard.user_groups.user_groups') }}
    </span>
    </div>
    <div class="content-wrapper">
        <div class="row">
            <div class="col-sm-12">
                @include('partials.errors')
                <form name="UserGroupForm" class="form-vertical" role="form" action="{{ cachet_route('dashboard.user_groups.create', [], 'post') }}" method="POST">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <fieldset>
                        <label for="user_group-name">Name</label>
                        <input type="text" name="name" id="user_group-name" required="required" value="" class="form-control">
                        <br>
                    </fieldset>

                    <div class="form-group">
                        <div class="btn-group">
                            <button type="submit" class="btn btn-success">{{ trans('forms.add') }}</button>
                            <a class="btn btn-default" href="{{ cachet_route('dashboard.user_groups') }}">{{ trans('forms.cancel') }}</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@stop
