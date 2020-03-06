<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    protected $fillable = [
        'group_id',
        'has_child_precent',
        'type',
    ];

    /**
     * 参加ユーザーを記録
     * 
     * @note プレミアムアカウントでなければuserリストは取れないため、使えない
     */
    // public function users()
    // {
    //     return $this->belongsToMany('App\Models\User', 'group_user', 'line_id', 'group_id');
    // }
}
