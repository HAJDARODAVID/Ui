<?php

use App\Livewire\TestComponent;

return [
    'test' => [
        'component-path' => TestComponent::class, // or the Livewire alias string
        'header-name'     => 'Edit user',       // optional, default "Modal"
        'header-style'    => 'font-weight: 600; font-size: 1rem;', // optional
        'max-width'       => '600px',           // optional, default "1140px"
        'stable'          => false,              // optional, default false — see note below
    ],
];
