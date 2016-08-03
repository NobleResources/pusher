<?php

namespace VanLonden\Pusher\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    public function getTable()
    {
        return config('pusher.tables.users');
    }
}