<?php

namespace App\Enum;

enum TaskStatus : string
{
    case complete = 'complete';
    case incomplete = 'incomplete';
}
