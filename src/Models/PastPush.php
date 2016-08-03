<?php

namespace VanLonden\Pusher\Models;

use Illuminate\Database\Eloquent\Model;

class PastPush extends Model
{
    protected $table = 'a_past_push';
    protected $guarded = [];

    public function errors()
    {
        return $this->hasMany(PastPushError::class);
    }

    public function getTable()
    {
        return config('pusher.tables.past_push');
    }

}