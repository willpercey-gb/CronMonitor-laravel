<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CronLearning extends Model
{
    protected $fillable  = ['cron_id', 'start_time', 'end_time', 'did_fail'];
}
