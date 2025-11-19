<?php

namespace Reaper\Ui\Trait;

/**
 * Trait used for converting style array to a string suitable for HTML style attribute.
 */
trait StyleArrayToString
{
    /**
     * Convert a key => value pair to a string for style usage.
     * 
     * @param array $array Style array for conversion.
     * @return string
     */
    protected function styleArrayToString(array $array): string
    {
        $output = [];
        foreach ($array as $key => $value) {
            $output[] = $key . ": " . $value;
        }

        return implode("; ", $output);
    }
}
