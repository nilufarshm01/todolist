<?php

namespace App\Enum;

enum TaskStatus : string
{
    case Done = 'Done';
    case InProgress = 'in_progress';
}
