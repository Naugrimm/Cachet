@extends('layout.dashboard')

@section('content')
    <div class="content-wrapper header-fixed">
    @include('partials.errors')
    <div class="row">
        <div class="col-xs-12 col-lg-offset-2 col-lg-8">
            <div class="text-center margin-bottom">
                <h1>{{ $appName }} {{ trans('dashboard.employees.manage.usergroups') }}</h1>
                <p>{{ trans('dashboard.employees.manage.description') }} <strong>{{ $employee->firstname }} {{ $employee->lastname }}</strong></p>
            </div>
            <form action="{{ cachet_route('dashboard.employees.edit', ['employee' => $employee->id]) }}" method="post">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                @if($userGroups->isNotEmpty())
                    <ul class="list-group">

                        @foreach($userGroups as $userGroup)
                            @include('partials.usergroup_input', compact($userGroup))
                        @endforeach
                    </ul>
                @endif

                <div class="text-right">
                    <button type="submit" class="btn btn-success">{{ trans('dashboard.employees.manage.update_usergroups') }}</button>
                </div>
            </form>
        </div>
    </div>
    </div>
@stop
