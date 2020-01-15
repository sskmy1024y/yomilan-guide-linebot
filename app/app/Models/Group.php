<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    /**
     * 参加ユーザーを
     */
    public function users()
    {
        return $this->belongsToMany('App\Models\User', 'group_user', 'line_id', 'group_id');
    }
}
