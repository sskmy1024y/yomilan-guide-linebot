<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    // プライマリーキーのカラム名
    protected $primaryKey = 'group_id';

    // プライマリーキーの型
    protected $keyType = 'string';

    // プライマリーキーは自動連番か？
    public $incrementing = false;

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
