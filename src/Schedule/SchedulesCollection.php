<?php

namespace matfish\ConsoleScheduler\Schedule;

use Illuminate\Support\Collection;

class SchedulesCollection extends Collection
{
    protected array $schedules;

    public function command(string $command): Schedule
    {
        $schedule = new Schedule($command);
        $this->push($schedule);

        return $schedule;
    }
}