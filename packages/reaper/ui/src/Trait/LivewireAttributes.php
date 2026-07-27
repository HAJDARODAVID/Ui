<?php

namespace Reaper\Ui\Trait;

/**
 * Trait used to set different Livewire attributes.
 */
trait LivewireAttributes
{
    /**
     * Click livewire action [wire.click] 
     * @var string|NULL
     */
    public $action = NULL;

    /**
     * Set the livewire action property
     * 
     * @return void 
     */
    public function setLivewireAction($action)
    {
        $this->action = $action;
        return;
    }
}
