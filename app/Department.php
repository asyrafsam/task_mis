<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    // Each department has many users
    public function users()
    {
        return $this->hasMany('App\User');
    }
}
