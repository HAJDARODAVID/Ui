<?php

namespace Reaper\Ui\View\Components;

use Closure;
use Illuminate\View\Component;
use Illuminate\Contracts\View\View;
use Reaper\Ui\Http\Services\SizeType;
use Reaper\Ui\Trait\StyleArrayToString;
use Reaper\Ui\Trait\ClassAttributesSetter;

class Btn extends Component
{
    use StyleArrayToString;
    use ClassAttributesSetter;

    /**
     * Define the text inside the btn 
     * @var string
     */
    public $txt = NULL;

    /**
     * Holds all the class attributes that come from the  __construct
     * @var array
     */
    public $classAtt = ['btn shadow'];

    /**
     * Holds all the style attributes that come from the  __construct
     * @var array
     */
    public $styleAtt = ['border-radius' => '0px !Important'];

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
        $this->styleAtt = $this->styleArrayToString($this->styleAtt);
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
    private function setBtnType(string $type): void
    {
        $attributes = explode('.', $type);

        /**btn color */
        if (isset($attributes[0])) $this->classAtt[] = $this->setColorAttribute($attributes[0], 'btn');

        /**btn size */
        if (isset($attributes[1])) $this->classAtt[] = $this->setSizeAttribute($attributes[1], 'btn');

        /**btn border radius */
        if (isset($attributes[2])) $this->setBorderSizeStyle($attributes[2]);
    }

    /**
     * Set the border radius size
     * 
     * @return void
     */
    private function setBorderSizeStyle($size): void
    {
        $this->styleAtt['border-radius'] = $size . ' !Important';
    }
}
