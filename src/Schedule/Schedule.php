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
        $descriptors = [
            0 => ["pipe", "r"],  // stdin
            1 => ["pipe", "w"],  // stdout
            2 => ["pipe", "w"]   // stderr
        ];
        
        $process = proc_open("php craft {$this->command}", $descriptors, $pipes);
        
        if (is_resource($process)) {
            // Close stdin as we don't need it
            fclose($pipes[0]);
            
            // Get stdout and stderr content
            $stdout = stream_get_contents($pipes[1]);
            $stderr = stream_get_contents($pipes[2]);
            
            // Close the pipes
            fclose($pipes[1]);
            fclose($pipes[2]);
            
            // Get the exit code
            $exitCode = proc_close($process);
            
            return [
                'stdout' => $stdout ? explode("\n", trim($stdout)) : [],
                'stderr' => $stderr ? explode("\n", trim($stderr)) : [],
                'exitCode' => $exitCode
            ];
        }
        
        // If proc_open failed
        return [
            'stdout' => [],
            'stderr' => ['Failed to start process'],
            'exitCode' => -1
        ];
    }

    public function getCommand(): string
    {
        return $this->command;
    }

    public function isDue(\DateTime $now): bool
    {
        return $this->expression->isDue($now);
    }
}