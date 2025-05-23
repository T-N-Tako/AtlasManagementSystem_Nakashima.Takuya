<?php

namespace App\Models\Users;


use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Users\Subjects;


use App\Models\Posts\Like;
use Auth;

class User extends Authenticatable
{
    use Notifiable;
    use softDeletes;

    // const CREATED_AT = null;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'over_name',
        'under_name',
        'over_name_kana',
        'under_name_kana',
        'mail_address',
        'sex',
        'birth_day',
        'role',
        'password'
    ];

    protected $dates = ['deleted_at'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function posts()
    {
        return $this->hasMany('App\Models\Posts\Post');
    }

    public function calendars()
    {
        return $this->belongsToMany('App\Models\Calendars\Calendar', 'calendar_users', 'user_id', 'calendar_id')->withPivot('user_id', 'id');
    }

    public function reserveSettings()
    {
        return $this->belongsToMany(
            'App\Models\Calendars\ReserveSettings',     // 対象モデル（予約設定）
            'reserve_setting_users',                    // 中間テーブル
            'user_id',                                  // 中間テーブル内での「自分のid」
            'reserve_setting_id'                        // 中間テーブル内での「相手のid」
        )->withPivot('id');                             // 中間テーブルの追加情報も取得可能
    }

    // ユーザーが受講している科目（追記）
    public function subjects()
    {
        return $this->belongsToMany(
            Subjects::class,     // 対象モデル
            'subject_users',     // 中間テーブル
            'user_id',          // 自分のID
            'subject_id'        // 関連する科目のID
        )->withTimestamps(); // リレーションの定義
    }

    // いいねしているかどうか
    public function is_Like($post_id)
    {
        return Like::where('like_user_id', Auth::id())->where('like_post_id', $post_id)->first(['likes.id']);
    }

    public function likePostId()
    {
        return Like::where('like_user_id', Auth::id());
    }
}
