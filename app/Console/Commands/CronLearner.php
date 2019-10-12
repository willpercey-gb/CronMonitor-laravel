<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Crons;
use App\CronLearning;
use Mail;

date_default_timezone_set('Europe/London');

class CronLearner extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cron:learn';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'A command to learn about your crons';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function duration($date1, $date2)
    {
        $date1 = strtotime($date1); //Test dates
        $date2 = strtotime($date2);
// Formulate the Difference between two dates
        $diff = abs($date2 - $date1);
        $minutes = floor($diff / 60);

        return $minutes;

    }

    public function handle()
    {
        file_get_contents('https://' . $_SERVER['HTTP_HOST'] . '/start/Mw==');
        $crons = Crons::where('learning', true)->get();
        foreach ($crons as $cron) {
            $cronLearn = CronLearning::where("cron_id", $cron->id)->where("start_time", $cron->start_time)->get();
            if ($cronLearn->isEmpty()) {
                /* Don't create new Learning entry unless Cron has ran already */
                if ($cron->start_time !== NULL && $cron->end_time !== NULL) {
                    $cronL = new CronLearning;
                    $cronL->cron_id = $cron->id;
                    $cronL->start_time = $cron->start_time;
                    $cronL->end_time = $cron->end_time;
                    $cronL->save();
                }
            } else {
                $this->info("Cron " . $cron->label . ' not empty');
            }
            $cronlearning = CronLearning::where('cron_id', $cron->id)->get();
            $times = [];
            foreach ($cronlearning as $cronlearn) {
                $duration = $this->duration($cronlearn->start_time, $cronlearn->end_time);
                $times[] = $duration;
            }
            if (count($times)) {
                if (count($times) > 100) {
                    $cronUpdate = Crons::find($cron->id);
                    $this->info("Turned off learning for " . $cron->label);
                    $cronUpdate->learning = 0;
                    $cronUpdate->save();
                }
                if ($cron->learning) {
                    $times = array_filter($times);
                    if (count($times) != 0) {
                        $average = array_sum($times) / count($times);
                        $cronUpdate = Crons::find($cron->id);
                        $cronUpdate->expected_duration = $average;
                        $cronUpdate->save();
                    } else {
                        //cron runs in seconds and is done - set expected dur to 0 and use allowance only.
                        $cronUpdate = Crons::find($cron->id);
                        $this->info("Set " . $cronUpdate->label . ' to exp dur 0');
                        $cronUpdate->expected_duration = 0;
                        $cronUpdate->save();
                    }
                }
            }
            file_get_contents('https://' . $_SERVER['HTTP_HOST'] . '/end/Mw==');
        }
    }
}
