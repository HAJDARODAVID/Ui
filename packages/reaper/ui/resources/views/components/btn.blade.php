@if ($link)
    <a
        @if(Route::has($link)) href="{{ route($link) }}" @endif
        class="btn {{ $btnColor }} @if($btnSize){{ $btnSize }} @endif shadow no-border-radius">
        <div class="d-flex align-items-center gap-2">
            @if ($iconName && $iconPosition == 'start')
                <i class="bi bi-{{ $iconName }}"></i>
            @endif
            {{ $slot }}{{ $text }}
            @if ($iconName && $iconPosition == 'end')
                <i class="bi bi-{{ $iconName }}"></i>
            @endif
        </div>
    </a>
@else
    <button
        type="button"
        class="btn {{ $btnColor }} @if($btnSize){{ $btnSize }} @endif shadow"
        style="border-radius: 0px!Important;"
        {{ $attributes }}
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
            {{ $slot }}{{ $text }}
            @if ($iconName && $iconPosition == 'end')
                <i class="bi bi-{{ $iconName }}"></i>
            @endif
        </div>
    </button>
@endif
