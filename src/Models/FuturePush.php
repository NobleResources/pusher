<?php

namespace VanLonden\Pusher\Models;

use Illuminate\Database\Eloquent\Model;

class FuturePush extends Model
{
    protected $guarded = [];

    public function users()
    {
        return $this->belongsToMany(
            config('pusher.user_model'),
            config('pusher.tables.future_push_user')
        );
    }

    public function getTable()
    {
        return config('pusher.tables.future_push');
    }
}