<?php

namespace Reaper\Ui\Trait;

use Reaper\Ui\Http\Services\SizeType;
use Reaper\Ui\Http\Services\ColorType;

/**
 * Trait used to set different Bootstrap class attributes.
 */
trait ClassAttributesSetter
{
    /**
     * Set the Bootstrap attribute for size.
     * 
     * @param string $value Needed size.
     * @param string $element BS prefix attribute for a element [btn, form-control].
     * @return string|NULL
     */
    protected function setSizeAttribute(string $value, string $element): string|NULL
    {
        if (SizeType::init()->allowedFor($element, $value)) return $element . '-' . $value;
        return NULL;
    }

    /**
     * Set the Bootstrap attribute for color.
     * 
     * @param string $value Needed size.
     * @param string $element BS prefix attribute for a element [btn, form-control].
     * @return string|NULL
     */
    protected function setColorAttribute(string $value, string $element): string|NULL
    {
        $colorAttribute = ColorType::byType($value);
        if ($colorAttribute->getColor()) return $element . '-' . $colorAttribute->getColorAttribute();
        return NULL;
    }
}
