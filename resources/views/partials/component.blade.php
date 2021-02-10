@if(Illuminate\Support\Facades\Auth::user() ||
    //isset($_SESSION['sp_employee']) ||
    $component->user_groups_id === 0
    )
    <li class="list-group-item {{ $component->group_id ? "sub-component" : "component" }}">
        @if($component->link)
        <a href="{{ $component->link }}" target="_blank" class="links">{!! $component->name !!}</a>
        @else
        {!! $component->name !!}
        @endif

        @if($component->description)
        <i class="ion ion-ios-help-outline help-icon" data-toggle="tooltip" data-title="{{ $component->description }}" data-container="body"></i>
        @endif
        @if(setting('display_badge_links'))
            <a target="_blank" href="{{ cachet_route('component.badge', ['component' => $component->getKey()]) }}">
                <i class="ion ion-image"></i>
            </a>
        @endif

        <div class="pull-right">
            <small class="text-component-{{ $component->status }} {{ $component->status_color }}" data-toggle="tooltip" title="{{ trans('cachet.components.last_updated', ['timestamp' => $component->updated_at_formatted]) }}">{{ $component->human_status }}</small>
        </div>
    </li>
@endif
