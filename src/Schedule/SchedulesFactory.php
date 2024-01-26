<?php

namespace matfish\ConsoleScheduler\Schedule;

use matfish\ConsoleScheduler\Plugin;

class SchedulesFactory
{
    public static function make(): SchedulesCollection
    {
        $schedulesCallback = Plugin::getInstance()->getSettings()->schedules;
        $collection = new SchedulesCollection();

        if ($schedulesCallback) {
            $schedulesCallback($collection);
        }

        return $collection;
    }
}