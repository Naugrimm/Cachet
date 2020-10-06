@extends('layout.master')

@section('content')

        <div class="section-timeline">
            <h1>{{ trans('cachet.incidents.scheduled_all') }}</h1>
            @foreach($allSchedules as $date => $schedules)
                @include('partials.schedules', [@compact($date), @compact($schedules)])
            @endforeach
        </div>

        <nav>
            <ul class="pager">
                @if($canPageBackward)
                    <li class="previous">
                        <a href="{{ cachet_route('schedules') }}?start_date={{ $previousDate }}" class="links">
                            <span aria-hidden="true">&larr;</span> {{ trans('pagination.previous') }}
                        </a>
                    </li>
                @endif
                @if($canPageForward)
                    <li class="next">
                        <a href="{{ cachet_route('schedules') }}?start_date={{ $nextDate }}" class="links">
                            {{ trans('pagination.next') }} <span aria-hidden="true">&rarr;</span>
                        </a>
                    </li>
                @endif
            </ul>
        </nav>

@stop

@section('bottom-content')
    @include('partials.footer')
@stop
