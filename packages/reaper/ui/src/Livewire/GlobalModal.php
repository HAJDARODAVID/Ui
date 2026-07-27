<?php

namespace Reaper\Ui\Livewire;

use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\Mechanisms\ComponentRegistry;
use Reaper\Ui\Services\GlobalModal\GlobalModalService;
use Reaper\Ui\Services\GlobalModal\ModalDto;

class GlobalModal extends Component
{
    public array $componentParams = [];

    public int $renderVersion = 0;

    /**
     * The registry key of the currently open modal (see config('global-modal')).
     * Kept as a plain public string - rather than the resolved ModalDto - because
     * Livewire only persists public properties across requests; render() resolves
     * this back into a ModalDto every time so it's never stale after a request
     * that didn't go through openGlobalModal() (e.g. clearComponent()).
     */
    public ?string $modalName = null;

    /**
     * Triggered from JS via Livewire.dispatch('open-global-modal', { component, params }).
     * $this->js() runs after the DOM morph, so the nested @livewire() child is already
     * in the DOM when Alpine receives the event and shows the overlay.
     */
    #[On('open-global-modal')]
    public function openGlobalModal(string $component, array $params = []): void
    {
        try {
            $modal = (new GlobalModalService)->make($component);

            // Resolve the underlying Livewire component now, so a bad/renamed
            // component-path in config('global-modal') fails here with a
            // friendly notification instead of crashing render()'s @livewire() call.
            app(ComponentRegistry::class)->getClass($modal->getComponentPath());
        } catch (\Throwable $th) {
            $this->dispatch('notify', ['message' => $th->getMessage(), 'type' => 'danger']);
            return;
        }

        $this->modalName = $component;
        $this->componentParams = $params;
        $this->renderVersion++;
        $this->dispatch('global-modal-open-overlay');

        // Dispatched after the child component above has been rendered into the DOM
        // (same request/morph as global-modal-open-overlay), so the freshly mounted
        // child's #[On('global-modal-component-ready')] listener can run its own
        // setup/data-loading method here instead of relying on mount().
        $this->dispatch('global-modal-component-ready');
    }

    /**
     * Called by Alpine's close() via $wire.clearComponent() so the child
     * component is unmounted on the next render.
     */
    public function clearComponent(): void
    {
        $this->componentParams = [];
        $this->renderVersion++;
    }

    public function render()
    {
        return view('reaper::livewire.global-modal', [
            'modalService' => $this->modalName ? (new GlobalModalService)->make($this->modalName) : new ModalDto(),
            'time' => now()->format('Y-m-d H:i:s.u'),
        ]);
    }
}
