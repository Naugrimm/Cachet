<div class="section-status">
    <div class="alert alert-{{ $systemStatus }}">
        {{ $systemMessage }}
        @if(setting('display_badge_links'))
            <div class="pull-right">
                <a target="_blank" href="{{ cachet_route('badge') }}">
                    <i class="ion ion-image"></i>
                </a>
            </div>
        @endif
    </div>
</div>
