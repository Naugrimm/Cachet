<h4>{{ formatted_date($date) }}</h4>
<div class="timeline">
    <div class="content-wrapper">
        @forelse($schedules as $schedule)
            <div class="moment {{ $loop->first ? 'first' : null }}">
                <div class="row event clearfix">
                    <div class="col-sm-1">
                        <div class="status-icon status-{{ $schedule->human_status }}" data-toggle="tooltip" title="{{ $schedule->human_status }}" data-placement="left">
                            <i class="{{ $schedule->latest_icon }}"></i>
                        </div>
                    </div>
                    <div class="col-xs-10 col-xs-offset-2 col-sm-11 col-sm-offset-0">
                        <div class="panel panel-message incident">
                            <div class="panel-heading">
                                @if($currentUser)
                                    <div class="pull-right btn-group">
                                        <a href="{{ cachet_route('dashboard.schedule.edit', ['id' => $schedule->id]) }}" class="btn btn-default">{{ trans('forms.edit') }}</a>
                                        <a href="{{ cachet_route('dashboard.schedule.delete', ['id' => $schedule->id], 'delete') }}" class="btn btn-danger confirm-action" data-method='DELETE'>{{ trans('forms.delete') }}</a>
                                    </div>
                                @endif
                                <strong>{{ $schedule->name }}</strong>
                                {{-- $schedule->isCompleted ? trans("cachet.incidents.scheduled_at", ["timestamp" => $incident->scheduled_at_diff]) : null --}}
                                <br>
                                <small class="date">
                                    <a href="{{ cachet_route('schedule', ['id' => $schedule->id]) }}" class="links"><abbr class="timeago" data-toggle="tooltip" data-placement="right" title="{{ $schedule->timestamp_formatted }}" data-timeago="{{ $schedule->timestamp_iso }}"></abbr></a>
                                </small>
                            </div>
                            <div class="panel-body markdown-body">
                                {!! $schedule->message !!}
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        @empty
            <div class="panel panel-message incident">
                <div class="panel-body">
                    <p>{{ trans('cachet.incidents.none') }}</p>
                </div>
            </div>
        @endforelse
    </div>
</div>

