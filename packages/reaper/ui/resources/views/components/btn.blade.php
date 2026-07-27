@php
    $tag = $route ? 'a' : 'button';
@endphp
<{{$tag}} 
    @if($route && Route::has($route)) href="{{ route($route) }}" @endif
    {{ $attributes->merge(['class' => implode(" ", $classAtt), 'style' => $styleAtt]) }}
    @if($disabled) disabled @endif
    @if ($stopPropagation) onclick="event.stopPropagation();" @endif
>
    <div class="d-flex align-items-center gap-2">
        @if($iconAtt['icon'] && $iconAtt['position'] == 'left')
            <div class=""><i class="{{ $iconAtt['icon'] }}"></i></div>
        @endif
        @if($txt || $slot != "") <div class="">{{ $txt }}{{ $slot }}</div> @endif
        @if($iconAtt['icon'] && $iconAtt['position'] == 'right')
            <div class=""><i class="{{ $iconAtt['icon'] }}"></i></div>
        @endif
    </div>  
</{{$tag}}>
