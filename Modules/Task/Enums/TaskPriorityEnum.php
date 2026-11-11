<?php

namespace Modules\Task\Enums;

enum TaskPriorityEnum: int
{
    case Low = 1;
    case Medium = 2;
    case High = 3;
    case Urgent = 4;

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
