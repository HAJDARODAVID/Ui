@if ($link)
    <a
        @if(Route::has($link)) href="{{ route($link) }}" @endif
        class="btn {{ $btnColor }} @if($btnSize){{ $btnSize }} @endif shadow no-border-radius">
        <div class="d-flex align-items-center gap-2">
            @if ($iconName && $iconPosition == 'start')
                <i class="bi bi-{{ $iconName }}"></i>
            @endif
            {{ $slot }}{{ $txt }}
            @if ($iconName && $iconPosition == 'end')
                <i class="bi bi-{{ $iconName }}"></i>
            @endif
        </div>
    </a>
@else
    <button
        type="button"
        {{ $attributes->merge([
            'class' => implode(' ', $classAttributes),
            'style' => implode('; ', $styleAttributes)
            ]) }}
        @if ($modal)
            data-trigger="global-modal" 
            data-component="{{ $modal }}"
        @endif
        @if ($action)
            wire:click="{{ $action }}('{{ $param }}')"
        @endif

        @if ($disabled) disabled @endif
        @if ($stopPropagation) onclick="event.stopPropagation();" @endif>
        <div class="d-flex align-items-center gap-2">
            @if($iconName)
                @if ($iconPosition == 'start')
                    <i class="bi bi-{{ $iconName }}"></i>
                @endif
            @endif
            {{ $slot }}{{ $txt }}
            @if ($iconName && $iconPosition == 'end')
                <i class="bi bi-{{ $iconName }}"></i>
            @endif
        </div>
    </button>
@endif

{{-- data-trigger="global-modal" data-component="worker-attendance-info" --}}
