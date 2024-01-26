<?php

namespace matfish\ConsoleScheduler\Schedule;

use Cron\CronExpression;

class Schedule
{
    use Frequencies;

    public const SUNDAY = 0;

    public const MONDAY = 1;

    public const TUESDAY = 2;

    public const WEDNESDAY = 3;

    public const THURSDAY = 4;

    public const FRIDAY = 5;

    public const SATURDAY = 6;

    protected string $command;
    protected ?CronExpression $expression;

    /**
     * @param string $command
     */
    public function __construct(string $command)
    {
        $this->command = $command;
        $this->expression = new CronExpression('* * * * *');
    }

    public function run()
    {
        exec("php craft {$this->command}", $output, $exitCode);
        return [$output,$exitCode];
    }

    public function getCommand(): string
    {
        return $this->command;
    }

    public function isDue(): bool
    {
        return $this->expression->isDue();
    }
}