<?php

namespace App\Console;

use App\WeekPubliction;
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
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')->hourly();
        $schedule->call(function (){
            $date=new \DateTime(date('Y-m-d h:i:s',(time()+28800)));
            $week=date('W',(time()+28810));
            $year=$date->format('Y');
            $period=$year.'-'.$week;
            $WeekPublication=new WeekPubliction(["period" => $period]);
            $WeekPublication->save();
        })->timezone("Asia/Shanghai")->weeklyOn(1,'0:00');//自动生成每周周报
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
