<button {{ $attributes->merge(['class' => implode(" ", $classAtt), 'style' => $styleAtt]) }}>
    {{ $slot }}{{ $txt }} 
</button>