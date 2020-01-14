<?php

namespace Models\App;

use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    /**
     * 参加ユーザーを
     */
    public function users()
    {
        return $this->belongsToMany('Models\App\User', 'group_user', 'line_id', 'group_id');
    }
}
