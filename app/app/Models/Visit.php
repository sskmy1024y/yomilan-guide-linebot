<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Visit extends Model
{
    protected $fillable = [
        'group_id',
        'start',
    ];

    /**
     * 生成したルートを取得
     */
    public function routes()
    {
        return $this->hasMany('App\Models\Route', 'visit_id');
    }

    /**
     * 生成したルートのうち最新を取得
     */
    public function lastRoute()
    {
        return $this->routes->first();
    }
}
