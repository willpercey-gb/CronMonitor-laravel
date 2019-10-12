<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Crons extends Model
{
    protected $table = 'crons';
    protected $fillable = ['label', 'owner_id', 'start_time', 'end_time', 'allowance', 'next_expected_start', 'expected_duration', 'learning', 'did_fail', 'error_message'];

    public function owner()
    {
        return $this->belongsTo(User::class);
    }
}
