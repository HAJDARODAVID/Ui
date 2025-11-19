<?php

namespace Reaper\Ui\Http\Services;

/**
 * Class for colors of elements available in the application.
 * This uses Bootstrap attributes
 */
class ColorType
{
    /**Initialized size */
    private $color = NULL;

    const COLOR_TYPE_PRIMARY   = 'pri';
    const COLOR_TYPE_SECONDARY = 'sec';
    const COLOR_TYPE_SUCCESS   = 'suc';
    const COLOR_TYPE_DANGER    = 'dan';
    const COLOR_TYPE_WARNING   = 'war';
    const COLOR_TYPE_INFO      = 'inf';
    const COLOR_TYPE_LIGHT     = 'lig';
    const COLOR_TYPE_DARK      = 'dar';
    const COLOR_TYPE_LINK      = 'lin';

    const COLORS_TYPES = [
        self::COLOR_TYPE_PRIMARY   => 'primary',
        self::COLOR_TYPE_SECONDARY => 'secondary',
        self::COLOR_TYPE_SUCCESS   => 'success',
        self::COLOR_TYPE_DANGER    => 'danger',
        self::COLOR_TYPE_WARNING   => 'warning',
        self::COLOR_TYPE_INFO      => 'info',
        self::COLOR_TYPE_LIGHT     => 'light gray-border-color',
        self::COLOR_TYPE_DARK      => 'dark',
        self::COLOR_TYPE_LINK      => 'link'
    ];

    private function __construct($color)
    {
        $this->color = $color;
    }

    /**
     * Initialize a new instance by type
     * 
     * @return ColorType|NULL
     */
    public static function byType($type): ColorType|NULL
    {
        if (key_exists($type, self::COLORS_TYPES)) return new self($type);
        return NULL;
    }

    /**
     * Initialize a new instance for primary color
     * 
     * @return ColorType
     */
    public static function primary()
    {
        return new self(self::COLOR_TYPE_PRIMARY);
    }

    /**
     * Initialize a new instance for secondary color
     * 
     * @return ColorType
     */
    public static function secondary()
    {
        return new self(self::COLOR_TYPE_SECONDARY);
    }

    /**
     * Initialize a new instance for success color
     * 
     * @return ColorType
     */
    public static function success()
    {
        return new self(self::COLOR_TYPE_SUCCESS);
    }

    /**
     * Initialize a new instance for danger color
     * 
     * @return ColorType
     */
    public static function danger()
    {
        return new self(self::COLOR_TYPE_DANGER);
    }

    /**
     * Initialize a new instance for warning color
     * 
     * @return ColorType
     */
    public static function warning()
    {
        return new self(self::COLOR_TYPE_WARNING);
    }

    /**
     * Initialize a new instance for info color
     * 
     * @return ColorType
     */
    public static function info()
    {
        return new self(self::COLOR_TYPE_INFO);
    }

    /**
     * Initialize a new instance for light color
     * 
     * @return ColorType
     */
    public static function light()
    {
        return new self(self::COLOR_TYPE_LIGHT);
    }

    /**
     * Initialize a new instance for dark color
     * 
     * @return ColorType
     */
    public static function dark()
    {
        return new self(self::COLOR_TYPE_DARK);
    }

    /**
     * Initialize a new instance for link color
     * 
     * @return ColorType
     */
    public static function link()
    {
        return new self(self::COLOR_TYPE_LINK);
    }

    /**
     * Get the initialized color
     * 
     * @return string
     */
    public function getColor()
    {
        return $this->color;
    }

    /**
     * Get the initialized color attribute
     * 
     * @return string
     */
    public function getColorAttribute()
    {
        return self::COLORS_TYPES[$this->color];
    }
}
