<?php

namespace Modules\Common\Enums;

/**
 * Colour palette shared by categories and labels. The frontend maps
 * each value to its own light/dark theme colours.
 */
enum ColorEnum: string
{
    case Slate = 'slate';
    case Red = 'red';
    case Orange = 'orange';
    case Amber = 'amber';
    case Green = 'green';
    case Teal = 'teal';
    case Sky = 'sky';
    case Blue = 'blue';
    case Indigo = 'indigo';
    case Violet = 'violet';
    case Pink = 'pink';
    case Rose = 'rose';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
