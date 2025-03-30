<?php

namespace App\Models\Users;

use Illuminate\Database\Eloquent\Model;

use App\Models\Users\User;

class Subjects extends Model
{
    const UPDATED_AT = null;


    protected $fillable = [
        'subject'
    ];

    // 科目を受講しているユーザー（追記）
    public function users()
    {
        return $this->belongsToMany(
            User::class,        // 対象モデル
            'subject_users',     // 中間テーブル
            'subject_id',       // 自分のID
            'user_id'           // 関連するユーザーのID
        )->withTimestamps(); // リレーションの定義
    }
}
