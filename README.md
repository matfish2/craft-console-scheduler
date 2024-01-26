# Console Scheduler

This package adds the ability to schedule console commands directly from code.

## Why?

While you may write a cron configuration entry for each task you need to schedule on your server, this can quickly
become a pain, because your task schedule is not recorded to source control and you must SSH into your server to view
your existing cron entries, add additional entries or change the schedule.

This package will allow you to:
1. Define only one command in `crontab` and the rest will be handled in code.
2. Use intuitive and readable fluent syntax to define the frequency.
3. Log all executed commands to a dedicated `console-scheduler-{date}.log` file, along with any output the command may generate and the exit code.

## Installation

1. Include the package:

```
composer require matfish/craft-console-scheduler
```

2. Install the plugin:

```
php craft plugin/install console-scheduler
```

## Initial Setup

Add the following line to your `crontab`

```
* * * * * /var/www/my-site/craft scheduler/run
```

This will ensure the scheduler runs every minute, and checks for due commands to run.
It is recommended to set the frequency according to the highest frequency your project requires.
E.g if the most frequent command you have runs hourly, there is no need to call the scheduler every minute, and you can use `0 * * * *` instead

## Usage

1. Create a `config/console-scheduler.php` file 
2. Add schedules according to the following example:

```php
return [
    'schedules' => static function (\matfish\ConsoleScheduler\Schedule\SchedulesCollection $schedule) {
        $schedule->command('activity-logs/logs/prune --days=30 --interactive=0')->monthly();
        $schedule->command('cache/clear')->daily()->at('23:00');
    }
];
```

Supported frequencies:

* `everyMinute()`
* `everyTwoMinutes()`
* `everyThreeMinutes()`
* `everyFourMinutes()`
* `everyFiveMinutes()`
* `everyTenMinutes()`
* `everyFifteenMinutes()`
* `everyThirtyMinutes()`
* `everyOddHour()`
* `everyTwoHours()`
* `everyThreeHours()`
* `everyFourHours()`
* `everySixHours()`
* `daily()`
* `dailyAt($time)`
* `sundays()`
* `mondays()`
* `tuesdays()`
* `wednesdays()`
* `thrusdays()`
* `fridays()`
* `satrudays()`
* `weekly()`
* `weeklyOn($dayOfWeek, $time)`
* `monthly()`
* `monthlyOn($dayOfMonth, $time)`
* `twiceMonthly($firstDay, $secondDay, $time)`
* `lastDayOfMonth($time)`
* `quarterly()`
* `quarterlyOn($dayOfQuarter, $time)`
* `yearly()`
* `yearlyOn($month, $dayOfMonth, $time)`

Methods without a specified time can be followed by an `at` method using fluent syntax. 
## License

You can try Console Scheduler in a development environment for as long as you like. Once your site goes live, you are
required
to purchase a license for the plugin. License is purchasable through
the [Craft Plugin Store](https://plugins.craftcms.com/console-scheduler).

For more information, see
Craft's [Commercial Plugin Licensing](https://craftcms.com/docs/4.x/plugins.html#commercial-plugin-licensing).

## Requirements

This plugin requires Craft CMS 4.0.0 or later.

## Contribution Guidelines

Community is at the heart of open-source projects. We are always happy to receive constructive feedback from users to
incrementally improve the product and/or the documentation.

Below are a few simple rules that are designed to facilitate interactions and prevent misunderstandings:

Please only open a new issue for bug reports. For feature requests and questions open a
new [Discussion](https://github.com/matfish2/craft-console-scheduler/discussions) instead, and precede [FR] to the
title.

If you wish to endorse an existing FR please just vote the OP up, while refraining from +1 replies.