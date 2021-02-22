@extends('layout.dashboard')

@section('content')
<div class="header fixed">
    <div class="sidebar-toggler visible-xs">
        <i class="ion ion-navicon"></i>
    </div>
    <span class="uppercase">
        <i class="ion ion-ios-email-outline"></i> {{ trans('dashboard.subscribers.subscribers') }}
    </span>
    @if($currentUser->isAdmin)
    <a class="btn btn-md btn-success pull-right" href="{{ cachet_route('dashboard.subscribers.create') }}">
        {{ trans('dashboard.subscribers.add.title') }}
    </a>
    @endif
    <div class="clearfix"></div>
</div>
<div class="content-wrapper header-fixed">
    <div class="row">
        <div class="col-sm-12">
            <p class="lead">
                {{ trans('dashboard.subscribers.description') }}
            </p>

            <div class="row">
                <div class="col-sm-12">
                    <form name="searchForm" role="form" method="GET" class="form-vertical">
                        <fieldset><label for="search">E-Mail</label>
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
                @foreach($subscribers as $subscriber)
                <div class="row striped-list-item">
                    <div class="col-xs-3">
                        <p>{{ trans('dashboard.subscribers.subscriber', ['email' => $subscriber->email, 'date' => $subscriber->created_at]) }}</p>
                    </div>
                    <div class="col-xs-3">
                        @if(is_null($subscriber->getOriginal('verified_at')))
                        <b class="text-danger">{{ trans('dashboard.subscribers.not_verified') }}</b>
                        @else
                        <b class="text-success">{{ trans('dashboard.subscribers.verified') }}</b>
                        @endif
                    </div>
                    <div class="col-xs-3">
                        @if($subscriber->allowedGroups->isNotEmpty())
                        {!! $subscriber->allowedGroups->map(function ($allowedGroup) {
                            return sprintf('<span class="label label-primary">%s</span>', $allowedGroup->group->name);
                        })->implode(' ') !!}
                        @else
                        <p>{{ trans('dashboard.subscribers.no_subscriptions') }}</p>
                        @endif
                    </div>
                    <div class="col-xs-3 text-right">
                        <a href="{{ URL::signedRoute(cachet_route_generator('subscribe.manage'), ['code' => $subscriber->verify_code]) }}" target="_blank" class="btn btn-success">{{ trans('forms.edit') }}</a>
                        <a href="{{ cachet_route('dashboard.subscribers.delete', [$subscriber->id], 'delete') }}" class="btn btn-danger confirm-action" data-method='DELETE'>{{ trans('forms.delete') }}</a>
                    </div>
                </div>
                @endforeach
            </div>
            <nav>
                <ul class="pager">
                    @if(!$subscribers->onFirstPage())
                        <li class="previous">
                            <a href="{{ $subscribers->previousPageUrl() }}" class="links">
                                <span aria-hidden="true">&larr;</span> {{ trans('pagination.previous') }}
                            </a>
                        </li>
                    @endif
                    @if($subscribers->hasMorePages())
                        <li class="next">
                            <a href="{{ $subscribers->nextPageUrl() }}" class="links">
                                {{ trans('pagination.next') }} <span aria-hidden="true">&rarr;</span>
                            </a>
                        </li>
                    @endif
                </ul>
            </nav>
        </div>
    </div>
</div>
@stop
