<div class="timeline schedule">
    <div class="panel panel-default">
        <div class="panel-heading">
            <strong>{{ trans('cachet.incidents.scheduled') }}</strong>
        </div>
        <div class="list-group">
            @if(count($scheduledMaintenance) === 0)
                <li class="list-group-item sub-component">
                    {{ trans('cachet.incidents.schedules_actually_empty') }}
                </li>
            @endif
            @foreach($scheduledMaintenance as $schedule)
            <div class="list-group-item" id="scheduled-{{ $schedule->id }}">
                <strong>{{ $schedule->name }}</strong> <small class="date"><abbr class="timeago" data-toggle="tooltip" data-placement="right" title="{{ $schedule->scheduled_at_formatted }}" data-timeago="{{ $schedule->scheduled_at_iso }}"></abbr></small>
                <div class="pull-right"><a href="#scheduled-{{ $schedule->id }}"><i class="ion ion-link"></i></a></div>
                <div class="markdown-body">
                    {!! $schedule->formatted_message !!}
                </div>
                @if($schedule->components->count() > 0)
                <hr>
                @foreach($schedule->components as $affectedComponent)
                <span class="label label-primary">{{ $affectedComponent->component->name }}</span>
                @endforeach
                @endif
            </div>
            @endforeach
        </div>
    </div>
    <a href="/schedules/">
        <btn class="btn btn-md btn-success">{{ trans('cachet.incidents.scheduled_all') }}</btn>
    </a>
</div>
