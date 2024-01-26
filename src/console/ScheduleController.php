<?php

namespace matfish\ConsoleScheduler\console;

use Craft;
use craft\console\Controller;
use craft\helpers\DateTimeHelper;
use matfish\ConsoleScheduler\Schedule\Schedule;
use matfish\ConsoleScheduler\Schedule\SchedulesFactory;
use yii\console\ExitCode;
use yii\helpers\BaseConsole;
use yii\log\Logger;

class ScheduleController extends Controller
{
    public $defaultAction = 'run';

    public function actionRun(): int
    {
        $time = DateTimeHelper::now();

        $this->log("Checking for commands to run at {$time->format('d-m-Y H:i')}");

        SchedulesFactory::make()->filter(function (Schedule $schedule) use ($time) {
            return $schedule->isDue($time);
        })->each(function (Schedule $schedule) {
            $this->log("Running command " . $schedule->getCommand(), true);
            $res = $schedule->run();
            if (is_array($res[0])) {
                $this->log('Output:', true);
                foreach ($res[0] as $row) {
                    $this->log($row, true);
                }
            }

            $this->log("Exit code " . $res[1], true);
        });

        $this->log("Done");

        return ExitCode::OK;
    }

    private function log($message, $toFile = false): void
    {
        $this->stdout($message . PHP_EOL, BaseConsole::FG_GREEN);

        if ($toFile) {
            Craft::getLogger()->log($message, Logger::LEVEL_INFO, 'console-scheduler');
        }
    }
}