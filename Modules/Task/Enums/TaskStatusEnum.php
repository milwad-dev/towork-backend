<?php

namespace Modules\Task\Enums;

enum TaskStatusEnum: string
{
    case Todo = 'todo';
    case InProgress = 'in_progress';
    case Done = 'done';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
