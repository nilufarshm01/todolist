<?php

namespace App\Enum;

enum TaskStatus : string //todo You have 2 taskStatus, one is a class and current one is an enum. Unfortunately you are using the wrong one in your project. If you pay attention, you would see that this class is faded by PHPSTORM.
{
    case Done = 'Done';
    case InProgress = 'in_progress';
}
