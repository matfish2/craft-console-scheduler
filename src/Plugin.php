<?php

namespace matfish\ConsoleScheduler;

use Craft;
use craft\base\Plugin as BasePlugin;
use craft\log\MonologTarget;
use matfish\ConsoleScheduler\models\Settings;
use Monolog\Formatter\LineFormatter;
use Psr\Log\LogLevel;

class Plugin extends BasePlugin
{
    public function init(): void
    {
        parent::init();

        if (Craft::$app->request->getIsConsoleRequest()) {
            $this->controllerNamespace = 'matfish\\ConsoleScheduler\\console';
            $this->_registerLogTarget();
        }

    }

    protected function createSettingsModel(): Settings
    {
        return new Settings();
    }

    private function _registerLogTarget(): void
    {
        Craft::getLogger()->dispatcher->targets[] = new MonologTarget([
            'name' => 'console-scheduler',
            'categories' => ['console-scheduler'],
            'level' => LogLevel::INFO,
            'logContext' => false,
            'allowLineBreaks' => false,
            'formatter' => new LineFormatter(
                "[%datetime%] %message%\n",
                'Y-m-d H:i:s',
            ),
        ]);
    }
}