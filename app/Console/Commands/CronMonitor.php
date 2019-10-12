<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Crons;
use App\CronLearning;
use Mail;
use App\Mail\CronError;
use Cron\CronExpression;

date_default_timezone_set('Europe/London');

class CronMonitor extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cron:check';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check all crons are running as expected';

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
    public function handle()
    {
        file_get_contents('https://' . $_SERVER['HTTP_HOST'] . '/start/Mg==');
        $crons = Crons::all();
        foreach ($crons as $cron) {
            if ($cron->next_expected_start !== NULL) {

                $cronScheduler = CronExpression::factory($cron->cron_pattern);
                $prevRun = $cronScheduler->getPreviousRunDate()->format('Y-m-d H:i:s');

                if (strtotime($prevRun) > strtotime($cron->start_time) && $cron->start_time !== NULL || strtotime($cron->next_expected_start) < strtotime("+30 seconds")) {
                    $this->doFail("Cron " . $cron->label . " did not meet expected start time", $cron);
                }
                $allowance = $cron->expected_duration + $cron->allowance;
                if (strtotime($cron->end_time) > strtotime($cron->start_time . ' +' . $allowance . ' minutes')) {
                    $this->doFail($cron->label . " Potentially failed, took longer than expected duration", $cron);
                }
            } else {
                $cronScheduler = CronExpression::factory($cron->cron_pattern);
                $cronUpdate = Crons::find($cron->id);
                $cronUpdate->next_expected_start = $cronScheduler->getNextRunDate();
                $cronUpdate->save();
            }
        }
        file_get_contents('https://' . $_SERVER['HTTP_HOST'] . '/end/Mg==');
    }

    protected function doFail($message, $cron)
    {
        $cron->did_fail = 1;
        $cron->error_message = str_replace($cron->label, '', $message);
        Mail::to($cron->owner->email)->send(
            new CronError($message)
        );
        $cron->save();
    }
}
