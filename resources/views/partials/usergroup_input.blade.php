<li class="list-group-item component">
    <div class="checkbox">
        <label for="component-{{ $userGroup->id }}">
            <input type="checkbox"
                   id="component-{{ $userGroup->id }}"
                   name="subscriptions[]"
                   value="{{ $userGroup->id }}"
                   @if (in_array($userGroup->id, $subscriptions))
                   checked="checked"
                @endif />
            {!! $userGroup->name !!}
        </label>
    </div>
</li>
