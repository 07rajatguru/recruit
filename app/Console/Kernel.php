<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        'App\Console\Commands\DailyReport',
        'App\Console\Commands\WeeklyReport',
        'App\Console\Commands\ReportTest',
        'App\Console\Commands\EveryMinute',
        'App\Console\Commands\MonthlyReport',
        'App\Console\Commands\TodosFrequency',
        'App\Console\Commands\TodosFrequencyCheck',
        'App\Console\Commands\ClientStatus',
        'App\Console\Commands\JobOpentoAll',
        'App\Console\Commands\TrainingSelectAllUser',
        'App\Console\Commands\ProcessManualSelectAllUsers',
        'App\Console\Commands\TodoCCStartdateDefault',
        //'App\Console\Commands\LeaveBalanceData',
        //'App\Console\Commands\UserLeaveCheck',
        'App\Console\Commands\EligibilityWorkingReport',
        'App\Console\Commands\BillsPCAdd',
        'App\Console\Commands\RecoveryPrePCAdd',
        'App\Console\Commands\PassiveClientList',
        'App\Console\Commands\ClientDataTimeline',
        'App\Console\Commands\PassiveClientListRemider',
        'App\Console\Commands\CandidateAutoScript',
        'App\Console\Commands\ExistCandidateAutoScript',
        'App\Console\Commands\GenerateEmployeeID',
        'App\Console\Commands\ProductivityReport',
        'App\Console\Commands\AddUsersEmailPassword',
        'App\Console\Commands\InterviewPriorEmail',
        'App\Console\Commands\InterviewOneHourPriorEmail',
        'App\Console\Commands\AfterIntrviewReminder',
        'App\Console\Commands\OpenJobOfCheckbox',
        'App\Console\Commands\AddClientHeirarchyWisePositionInJobs',
        'App\Console\Commands\ApplicantCandidatesReport',
        'App\Console\Commands\AddUserOtherInfomations',
        'App\Console\Commands\SalaryReminder',
        'App\Console\Commands\NewCandidateEmail',
        'App\Console\Commands\ClientAutoGenerateReport',
        'App\Console\Commands\LeaveBalanceData',
        'App\Console\Commands\SeekLeaveBalance',
        'App\Console\Commands\AddLeaveBalance',
        'App\Console\Commands\SetAttendanceOfWorkPlanning',
        'App\Console\Commands\AddTotalHoursOneTime',
        'App\Console\Commands\DailyReportNew',
        'App\Console\Commands\SentWorkPlanningStatusReminder',
        'App\Console\Commands\PendingWorkPlanningReminderScript',
        'App\Console\Commands\LeaveApplicationReminder',
        'App\Console\Commands\ListofHolidaysReminder',
        'App\Console\Commands\NewJobOpentoall',
        'App\Console\Commands\AddYesterdayWorkPlanning',
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')
        //          ->hourly();

        //$schedule->command('report:daily')->dailyAt('20:00');
        //$schedule->command('report:weekly')->weeklyOn(6, '20:00');
    }

    /**
     * Register the Closure based commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        require base_path('routes/console.php');
    }
}
