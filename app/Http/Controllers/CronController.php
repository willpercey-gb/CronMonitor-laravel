<?php

namespace App\Http\Controllers;

date_default_timezone_set('Europe/London');

use Illuminate\Http\Request;
use App\Crons;
use Carbon\Carbon;
use Cron\CronExpression;
use Mail;
use App\Mail\CronError;

class CronController extends Controller
{
    public function start($id)
    {
        //decode cron ID
        $ID = base64_decode($id);
        //find cron
        $cron = Crons::findOrFail($ID);
        $cronScheduler = CronExpression::factory($cron->cron_pattern);
        $cron->start_time = date("Y-m-d H:i:s");
        $cron->end_time = NULL;
        $cron->did_fail = 0;
        $cron->next_expected_start = $cronScheduler->getNextRunDate()->format('Y-m-d H:i:s');
        $cron->save();

        header("Content-type: plain/text");
        return 'started';
    }

    public function end($id)
    {
        $id = base64_decode($id);
        $cron = Crons::findOrFail($id);
        $cron->end_time = date("Y-m-d H:i:s");
        if ($cron->did_fail == 1) {
            $cron->did_fail = 0;
            Mail::to($cron->owner->email)->send(
                new CronError($cron->label . " Previously marked as failed has now completed")
            );
        }
        $cron->save();
        header("Content-type: plain/text");

        return 'cron marked as finished';
    }

}
