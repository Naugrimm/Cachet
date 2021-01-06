@extends('layout.master')

@section('content')

<div class="pull-right">
    <p><a class="btn btn-success btn-outline" href="{{ cachet_route('status-page') }}"><i class="ion ion-home"></i></a></p>
</div>

<div class="clearfix"></div>

@include('partials.errors')

<div class="row">
    <div class="col-xs-12 col-lg-offset-2 col-lg-8">
        <div class="text-center margin-bottom">
            <h1>{{ $appName }} {{ trans('cachet.subscriber.manage.notifications') }}</h1>
            <p>{{ trans('cachet.subscriber.manage.notifications_for') }} <strong>{{ $subscriber->email }}</strong></p>
        </div>
        <form action="{{ URL::signedRoute(cachet_route_generator('subscribe.manage'), ['code' => $subscriber->verify_code]) }}" method="post">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            @if($userGroups->isNotEmpty())
            <ul class="list-group">

                @foreach($userGroups as $userGroup)
                @include('partials.usergroup_input', compact($userGroup))
                @endforeach
            </ul>
            @endif

            <div class="text-right">
                <button type="submit" class="btn btn-success">{{ trans('cachet.subscriber.manage.update_subscription') }}</button>
            </div>
        </form>
    </div>
</div>
@stop

@section('bottom-content')
    @include('partials.footer')
@stop
