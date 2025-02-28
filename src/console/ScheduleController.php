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
    public ?string $test = null;

    public function options($actionID): array
    {

        $options = parent::options($actionID);

        $options[] = 'test';

        return $options;
    }


    public function actionRun(): int
    {
        $time = $this->getDateTime();

        $this->log("Checking for commands to run at {$time->format('d-m-Y H:i')}");

        SchedulesFactory::make()->filter(function (Schedule $schedule) use ($time) {
            return $schedule->isDue($time);
        })->each(function (Schedule $schedule) {
            $this->log("Running command " . $schedule->getCommand(), true);
            $result = $schedule->run();
            
            if (!empty($result['stdout'])) {
                $this->log('Output:', true);
                foreach ($result['stdout'] as $row) {
                    $this->log($row, true);
                }
            }

            if (!empty($result['stderr'])) {
                $this->log('Errors:', true);
                foreach ($result['stderr'] as $row) {
                    $this->log($row, true, true);
                }
            }

            $this->log("Exit code " . $result['exitCode'], true);
        });

        $this->log("Done");

        return ExitCode::OK;
    }

    private function log($message, $toFile = false, $isError = false): void
    {
        $color = $isError ? BaseConsole::FG_RED : BaseConsole::FG_GREEN;
        $this->stdout($message . PHP_EOL, $color);

        if ($toFile) {
            $level = $isError ? Logger::LEVEL_ERROR : Logger::LEVEL_INFO;
            Craft::getLogger()->log($message, $level, 'console-scheduler');
        }
    }

    protected function getDateTime(): \DateTime
    {
        if ($this->test && getenv('CRAFT_ENVIRONMENT') === 'dev') {
            return DateTimeHelper::toDateTime($this->test, true);
        }

        return DateTimeHelper::now();
    }
}