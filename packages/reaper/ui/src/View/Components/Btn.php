<?php

namespace Reaper\Ui\View\Components;

use Closure;
use Illuminate\View\Component;
use Illuminate\Contracts\View\View;
use Reaper\Ui\Trait\StyleArrayToString;
use Reaper\Ui\Trait\AttributesSetter;

class Btn extends Component
{
    use StyleArrayToString;
    use AttributesSetter;

    /**Element type */
    protected $element = 'btn';

    /**
     * Define the text inside the btn 
     * @var string
     */
    public $txt = NULL;

    /**
     * If the btn should be a link type pass the route name into this property 
     * @var string
     */
    public $route = NULL;

    /**
     * Disable the element by setting this property to TRUE 
     * @var string
     */
    public $disabled = FALSE;

    /**
     * Define the icon attributes 
     * @var string
     */
    public $iconAtt = [
        'icon' => NULL,
        'position' => 'left',
    ];

    /**
     * Create a new component instance.
     */
    public function __construct(
        $txt = NULL,
        $att = NULL,
        $iconAtt = NULL,
        $route = NULL,
        $disabled = FALSE,
    ) {
        $this->txt = $txt;
        $this->route = $route;
        $this->disabled = $disabled;
        $this->classAtt = ['btn shadow'];
        $this->styleAtt = ['border-radius' => '0px !Important'];

        $this->setAttributes($att);
        $this->setIcon($iconAtt);
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
     * Set the border radius size
     * 
     * @return void
     */
    private function setBorderSizeStyle($size): void
    {
        $this->styleAtt['border-radius'] = $size . ' !Important';
    }

    /**
     * set the btn icon attribute
     * 
     * @return void 
     */
    public function setIcon($iconAtt)
    {
        $iconAtt = explode('.', $iconAtt);
        /**If nothing is set, return void */
        if ($iconAtt[0] == "" || $iconAtt[0] == NULL) return;

        /**If only one item in array treat like a BS-Icon name */
        if (count($iconAtt) == 1) {
            $iconExplosion = explode(':', $iconAtt[0]);
            $iconName = count($iconExplosion) == 1 ? $iconExplosion[0] : $iconExplosion[1];
            $this->iconAtt['icon'] = $this->checkIfBootstrapIconNameIsOk($iconName);
            return;
        }

        /**Multiple attributes */
        foreach ($iconAtt as $att) {
            $attExplosion = explode(':', $att);

            switch ($attExplosion[0]) {
                case 'icon':
                    $this->iconAtt['icon'] = isset($attExplosion[1]) ? $this->checkIfBootstrapIconNameIsOk($attExplosion[1]) : NULL;
                    break;
                case 'position':
                    $this->iconAtt['position'] = isset($attExplosion[1]) ? $attExplosion[1] : NULL;
                    break;
            }
        }
    }

    public function checkIfBootstrapIconNameIsOk($name)
    {
        if (substr($name, 0, 6) == "bi bi-") return $name;
        return "bi bi-" . $name;
    }
}
