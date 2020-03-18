<div class="section-status">
    <div class="alert alert-{{ $systemStatus }}">
        {{ $systemMessage }}
        @if(config('badges.enabled'))
            <div class="pull-right">
                <a target="_blank" href="{{ cachet_route('badge') }}">
                    <i class="ion ion-ios-albums-outline"></i>
                </a>
            </div>
        @endif
    </div>
</div>
