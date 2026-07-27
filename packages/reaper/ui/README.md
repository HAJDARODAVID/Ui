# Reaper UI

Custom Bootstrap-based UI components for Laravel, plus a Livewire-powered global modal system.

## Requirements

- Laravel (tested with the `laravel/laravel` skeleton, v10)
- `livewire/livewire` `^3.0` (installed automatically as a dependency)
- Alpine.js (ships with Livewire v3) — used by the global modal for open/close transitions
- Bootstrap CSS + [Bootstrap Icons](https://icons.getbootstrap.com/) — the components emit Bootstrap classes (`btn`, `btn-primary`, `bi bi-*`, etc.) and expect Bootstrap to be loaded in the host app

## Installation

This package is currently distributed as a local **path repository**, not from Packagist. In the consuming app's root `composer.json`:

```json
{
    "require": {
        "reaper/ui": "dev-master"
    },
    "repositories": [
        {
            "type": "path",
            "url": "packages/reaper/ui",
            "options": {
                "symlink": true
            }
        }
    ]
}
```

Then install:

```bash
composer require reaper/ui:dev-master
```

The service provider is picked up automatically via Laravel package discovery (declared in `composer.json` under `extra.laravel.providers`) — no manual registration needed in `config/app.php`.

### Publishing assets

The package exposes two publish tags:

| Tag | Publishes | Destination |
|---|---|---|
| `reaper-ui-config` | `config/global-modal.php` | `config/global-modal.php` |
| `reaper-ui-assets` | `resources/js/global-modal.js` | `resources/js/vendor/reaper-ui/global-modal.js` |

Publish everything:

```bash
php artisan vendor:publish --provider="Reaper\Ui\ReaperUiServiceProvider"
```

Or publish selectively:

```bash
php artisan vendor:publish --tag=reaper-ui-config
php artisan vendor:publish --tag=reaper-ui-assets
```

You only need `reaper-ui-config` if you plan to define [global modals](#global-modal) (see below) — the file ships empty by default. You need `reaper-ui-assets` if you plan to use the global modal's trigger helpers from plain HTML/JS (see [Opening a modal](#opening-a-modal)).

### Or use the package's own publish command

The package also ships `reaper-ui:publish`, a thin wrapper around `vendor:publish` that's scoped to **only** this package's files — so it never touches publishables from other packages, unlike a bare `php artisan vendor:publish`.

```bash
# publish everything from reaper/ui
php artisan reaper-ui:publish

# publish only specific tags
php artisan reaper-ui:publish --tag=reaper-ui-config
php artisan reaper-ui:publish --tag=reaper-ui-config --tag=reaper-ui-assets

# overwrite files that already exist at the destination
php artisan reaper-ui:publish --force
```

## What the package registers

On boot, `ReaperUiServiceProvider` (`src/ReaperUiServiceProvider.php`):

- Loads package views under the `reaper::` namespace (`resources/views`)
- Registers the `<x-reaper.ui::btn>` Blade component
- Registers the `global-modal` Livewire component
- Registers a test route group under the `reaper-ui` prefix/name (`routes/web.php` — currently just a `GET /reaper-ui/test` sanity-check route, not meant for app use)
- Registers the `reaper-ui:publish` Artisan command (console only)

## Button component

`<x-reaper.ui::btn>` renders a Bootstrap button (or an `<a>` if `link` is given and resolves to a named route).

```blade
<x-reaper.ui::btn text="Save" type="pri.sm" />

<x-reaper.ui::btn text="Delete" type="dan" icon="trash" wClickMethod="delete" wClickParam="{{ $id }}" />
```

### Props

| Prop | Type | Description |
|---|---|---|
| `text` | string | Button label (alternative to passing the slot) |
| `type` | string | Dot-separated `color.size`, see below |
| `icon` | string | Dot-separated `icon-name.position`, see below |
| `wClickMethod` / `wClickParam` | string | Renders `wire:click="{method}('{param}')"` |
| `action` / `param` | string | Alternative to `wClickMethod`/`wClickParam` — same `wire:click="{action}('{param}')"` output, takes precedence if both are set |
| `link` | string | A named route. If set (and it exists via `Route::has()`), renders an `<a href="{{ route(...) }}">` instead of a `<button>` |
| `disabled` | bool | Adds the `disabled` attribute |
| `stopPropagation` | bool | Adds `onclick="event.stopPropagation();"` |

Regular Blade attributes (`class`, `id`, ...) can be passed normally and are merged onto the root `<button>` element (not the `<a>` variant).

### `type` — color and size

Dot-separated, `color[.size]`:

```
type="pri.sm"
```

- Color (required position): one of `pri`, `sec`, `suc`, `dan`, `war`, `inf`, `lig`, `dar`, `lin` (primary, secondary, success, danger, warning, info, light, dark, link) → adds `btn-{color}`. Defaults to `pri` if omitted/unrecognized.
- Size (optional, second position): `sm` or `lg` → adds `btn-{size}`. Any other value is silently ignored.

### `icon` — icon

Renders a [Bootstrap Icon](https://icons.getbootstrap.com/) (`<i class="bi bi-...">`) next to the label.

```blade
{{-- shorthand: just the icon name --}}
<x-reaper.ui::btn text="Edit" icon="pencil" />

{{-- explicit form: icon + position (start/end, default start) --}}
<x-reaper.ui::btn text="Next" icon="arrow-right.end" />
```

Two names are aliased for backwards compatibility with the host app's original icon set: `save` → `bi bi-floppy`, `add` → `bi bi-plus-circle`. Any other name is used as-is (`bi bi-{name}`).

## Global Modal

A single Livewire-driven modal shell that can mount **any** Livewire component inside it, driven either from server-side Livewire code or from a plain DOM click/JS event — useful for opening the same modal from a Blade button, an Alpine component, or fully static HTML without wiring up Livewire on the trigger element itself.

### 1. Configure the modals you want available

After publishing `reaper-ui-config`, edit `config/global-modal.php`:

```php
<?php

return [
    'edit-user' => [
        'component-path' => \App\Livewire\Users\EditUserForm::class, // or the Livewire alias string
        'header-name'     => 'Edit user',       // optional, default "Modal"
        'header-style'    => 'font-weight: 600; font-size: 1rem;', // optional
        'max-width'       => '600px',           // optional, default "1140px"
        'stable'          => false,              // optional, default false — see note below
    ],
];
```

Each top-level key (`edit-user` above) is the **modal name** you reference when opening it. `component-path` is the only required field.

`stable` controls the Livewire `wire:key` used for the nested component: leave it `false` (default) so the child component is force-remounted every time the modal opens (fresh state per open, even with the same params); set it `true` if you want the same params to reuse an existing component instance instead of remounting.

### 2. Render the modal shell once in your layout

```blade
{{-- e.g. resources/views/layouts/app.blade.php, near the end of <body> --}}
<livewire:global-modal />
```

### 3. Load the JS trigger helper (optional — only for non-Livewire triggers)

Publish `reaper-ui-assets`, then import and initialize it once (e.g. in `resources/js/app.js`):

```js
import { registerGlobalModal } from './vendor/reaper-ui/global-modal.js';

registerGlobalModal();
```

This wires up two things globally:

- A `window` listener for a `open-application-modal` CustomEvent
- A `document` click listener for elements matching `#global-modal` or `[data-trigger="global-modal"]`

### Opening a modal

**From a plain button (no Livewire needed on the trigger):**

```html
<button data-trigger="global-modal" data-component="edit-user" data-params='{"id": 42}'>
    Edit user
</button>
```

**By dispatching the CustomEvent yourself (e.g. from Alpine or vanilla JS):**

```js
window.dispatchEvent(new CustomEvent('open-application-modal', {
    detail: { modal: 'global', component: 'edit-user', params: { id: 42 } }
}));
```

**From server-side Livewire code**, dispatch the browser event `open-global-modal` directly:

```php
$this->dispatch('open-global-modal', component: 'edit-user', params: ['id' => 42]);
```

In all cases, `component` must match a key in `config/global-modal.php`, and `params` is passed into the mounted component as its `params` prop/argument.

### Inside the mounted component

The component referenced by `component-path` receives a `params` array (from `mount(array $params)` or a public `$params` property, depending on how you write it). Optionally listen for `global-modal-component-ready` to run setup logic once it's live in the DOM:

```php
use Livewire\Attributes\On;

class EditUserForm extends Component
{
    public array $params = [];

    #[On('global-modal-component-ready')]
    public function onReady(): void
    {
        // e.g. load the user record now that params are set
    }
}
```

### Closing

The modal closes when the user clicks the backdrop, clicks the `×` close button, or presses <kbd>Esc</kbd>. There's no manual "close" call needed from inside the child component for the common case.

## Notes / known limitations

- The `routes/web.php` file registered under the `reaper-ui` prefix currently only contains a placeholder test route (`GET /reaper-ui/test`) — it's not part of the public API and can be ignored.
- Only `sm` and `lg` sizes are honored on `<x-reaper.ui::btn>`.
