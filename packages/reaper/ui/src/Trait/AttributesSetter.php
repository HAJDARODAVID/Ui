<?php

namespace Reaper\Ui\Trait;

use Reaper\Ui\Http\Services\SizeType;
use Reaper\Ui\Http\Services\ColorType;

/**
 * Trait used to set different Bootstrap class attributes.
 */
trait AttributesSetter
{
    /**
     * Holds all the class attributes that come from the  __construct as the $att property
     * @var array
     */
    public $classAtt = [];

    /**
     * Holds all the style attributes that come from the  __construct as the $att property
     * @var array
     */
    public $styleAtt = [];

    /**
     * flag for the stopPropagation() attribute
     * @var bool
     */
    public $stopPropagation = FALSE;

    /**
     * Set the Bootstrap attribute for size.
     * 
     * @param string $value Needed size.
     * @param string $element BS prefix attribute for a element [btn, form-control].
     * @return string|null
     */
    protected function setSizeAttribute(string $value, string $element): string|null
    {
        if (SizeType::init()->allowedFor($element, $value)) return $element . '-' . $value;
        return null;
    }

    /**
     * Set the Bootstrap attribute for color.
     * 
     * @param string $value Needed color.
     * @param string $element BS prefix attribute for a element [btn, form-control].
     * @return string|null
     */
    protected function setColorAttribute(string $value, string $element): string|null
    {
        $colorAttribute = ColorType::byType($value);
        if ($colorAttribute->getColor()) return $element . '-' . $colorAttribute->getColorAttribute();
        return null;
    }

    /**
     * Return the default color attribute.
     * This will check the config file 'reaper-ui.php' for a default value. 
     * 
     * @param string $element BS prefix attribute for a element [btn, form-control].
     * @param string|null $value Default value.
     * @return string|null
     */
    protected function setDefaultColorAttribute(string $element, ?string $value = null): string|null
    {
        /**If the $value is null check the config file */
        if (is_null($value)) {
            //TODO:fixe this
        }
        $colorAttribute = ColorType::byType($value);
        if ($colorAttribute->getColor()) return $element . '-' . $colorAttribute->getColorAttribute();
        return null;
    }

    /**
     * This will set up the main classes for the btn.
     * Define here the size, color[for now]
     * 
     * @param string $type Define the attributes using the [.] like a separator.
     * TypeDefinition: Color.Size.BorderRadius
     * @return void  
     */
    private function setAttributes(string $att): void
    {
        $attributes = explode('.', $att);

        foreach ($attributes as $attribute) {
            /**Separate the key from the value */
            list($key, $value) = explode(':', $attribute);

            switch ($key) {
                case 'type':
                    $this->classAtt['color'] = $this->setColorAttribute($value, $this->element);
                    break;
                case 'size':
                    $this->classAtt['size'] = $this->setSizeAttribute($value, $this->element);
                    break;
                case 'stop-propagation':
                    $this->stopPropagation = TRUE;
                    break;

                default:
                    # code...
                    break;
            }
        }

        // /**btn border radius */
        // if (isset($attributes[2])) $this->setBorderSizeStyle($attributes[2]);
    }
}
