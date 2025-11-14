<?php

namespace Reaper\Ui\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use Reaper\Ui\Http\Services\SizeType;

class Btn extends Component
{
    /**
     * Define the text inside the btn 
     * @var string
     */
    public $txt = NULL;

    /**
     * Holds all the class attributes that come from the  __construct
     * @var array
     */
    public $classAtt = ['btn'];

    /**
     * Holds all the style attributes that come from the  __construct
     * @var array
     */
    public $styleAtt = ['border-radius' => '0px'];

    /**
     * Create a new component instance.
     */
    public function __construct(
        $txt = NULL,
        $type = NULL,
    ) {
        $this->txt = $txt;

        $this->setBtnType($type);
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('reaper::components.btn');
    }

    /**
     * This will set up the main classes for the btn.
     * Define here the size, color[for now]
     * 
     * @param string $type Define the attributes using the [.] like a separator.
     * TypeDefinition: Color.Size.BorderRadius
     * @return void  
     */
    private function setBtnType(string $type)
    {
        $attributes = explode('.', $type);

        /**btn color */

        /**btn size */
        if (isset($attributes[1])) $this->setBtnSizeAttribute($attributes[1]);

        /**btn border radius */
        if (isset($attributes[2])) {
        }
    }

    /**
     * Set the size class attribute
     * 
     * @return void
     */
    private function setBtnSizeAttribute($size)
    {
        if (SizeType::init()->allowedForBtn($size)) $this->classAtt[] = 'btn-' . $size;
    }
}
