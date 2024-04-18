<?php
// App/Models/Admin.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Admin extends Model
{
    protected $table = 'activity_logs'; // Name of your user activities table

    public function insert_activity($userName, $activity, $timestamp)
    {
        $this->create([
            'user_name' => $userName,
            'activity' => $activity,
            'timestamp' => $timestamp,
        ]);
    }
}
