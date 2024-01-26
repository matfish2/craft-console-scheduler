<?php

namespace matfish\ConsoleScheduler\models;

use craft\base\Model;

class Settings extends Model
{
    public ?\Closure $schedules = null;
}