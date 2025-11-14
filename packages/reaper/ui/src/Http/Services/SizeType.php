<?php

namespace Reaper\Ui\Http\Services;

/**
 * Class for sizes of elements available in the application.
 * This uses Bootstrap attributes
 */
class SizeType
{
    /**Initialized size */
    private $size = NULL;

    const SIZE_SMALL       = 'sm';
    const SIZE_MEDIUM      = 'md';
    const SIZE_LARGE       = 'lg';
    const SIZE_EXTRA_LARGE = 'xl';
    const SIZE_XXL         = 'xxl';

    const SIZE_TYPES = [
        self::SIZE_SMALL       => 'Small',
        self::SIZE_MEDIUM      => 'Medium',
        self::SIZE_LARGE       => 'Large',
        self::SIZE_EXTRA_LARGE => 'Extra large',
        self::SIZE_XXL         => 'XXL',
    ];

    /**Defines here all the attributes allowed on a btn element */
    private $allowedForBtn = [self::SIZE_SMALL, self::SIZE_LARGE];

    private function __construct($size = NULL)
    {
        $this->size = $size;
    }

    /**
     * Create a new instance the static way.
     * 
     * @return SizeTypes
     */
    public static function init()
    {
        return new self();
    }

    /**
     * Check if the given can be added to a btn element
     * 
     * @return bool
     */
    public function allowedForBtn($size)
    {
        return in_array($size, $this->allowedForBtn);
    }
}
