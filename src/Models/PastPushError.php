<?php

namespace VanLonden\Pusher\Models;

use Illuminate\Database\Eloquent\Model;

class PastPushError extends Model
{
    protected $table = 'a_past_push_errors';
    protected $guarded = [];

    public function getTable()
    {
        return config('pusher.tables.past_push_errors');
    }
}