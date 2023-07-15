<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->call(function () {
            // 現在時刻から10分後のviewingを取得
            $now = Carbon::now();
            $target_time = $now->addMinutes(10);
    
            $viewings = Viewing::where('start_time', $target_time)->get();
    
            // 各viewingのrequesterとapproversに通知
            foreach($viewings as $viewing) {
                $viewing->requester->notify(new MovieStartingSoon($viewing));
                foreach($viewing->approvers as $approver) {
                    $approver->notify(new MovieStartingSoon($viewing));
                }
            }
        })->everyMinute();
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
