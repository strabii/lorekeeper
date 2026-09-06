<?php

namespace App\Console\Commands;

use App\Models\Daily\Daily;
use App\Models\Daily\DailyTimer;
use Carbon\Carbon;
use Illuminate\Console\Command;

class update_timed_daily extends Command {
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update-timed-daily';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Hides timed daily when expired, or sets it active if ready.';

    /**
     * Create a new command instance.
     */
    public function __construct() {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle() {
        // activate or deactivate dailies
        $hidedaily = Daily::where('is_timed_daily', 1)->where('is_active', 1)->where('start_at', '>', Carbon::now())
            ->orWhere('is_timed_daily', 1)->where('is_active', 1)->where('end_at', '<', Carbon::now())->get();

        $showdaily = Daily::where('is_timed_daily', 1)->where('is_active', 0)->where('start_at', '<=', Carbon::now())->where('end_at', '>=', Carbon::now())
            ->orWhere('is_timed_daily', 1)->where('is_active', 0)->where('start_at', '<=', Carbon::now())->whereNull('end_at')->get();

        // hide dailies that have is_timed_daily set, but does NOT have an end period specified.
        // requires dailies with loop off to work
        $hideLimitedDaily = Daily::where('is_timed_daily', 1)->where('is_loop', 0)->where('is_active', 1)->whereNull('end_at')->get();

        // set daily that should be active to active
        foreach ($showdaily as $showdaily) {
            $showdaily->is_active = 1;
            $showdaily->save();
        }
        // hide daily that should be hidden now
        foreach ($hidedaily as $hidedaily) {
            $hidedaily->is_active = 0;
            $hidedaily->save();
        }
        // hide loop-off no-time-period dailies for users with maxxed out claims
        foreach ($hideLimitedDaily as $daily) {
            $dailyUsers = DailyTimer::where('daily_id', $daily->id)->get();
            foreach ($dailyUsers as $dailyUserMaxStep) {
                // then set the user's Daily Timer and set is_limited 1
                if ($dailyUserMaxStep->step >= $daily->maxStep) {
                    $dailyUserMaxStep->is_limited = 1;
                    $dailyUserMaxStep->save();
                } else {
                    // if new rewards were added that exceed the user's current step, then we turn it off
                    $dailyUserMaxStep->is_limited = 0;
                    $dailyUserMaxStep->save();
                }
            }
        }
    }
}
