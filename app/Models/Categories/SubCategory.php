<?php

namespace App\Models\Categories;

use Illuminate\Database\Eloquent\Model;

class SubCategory extends Model
{
    const UPDATED_AT = null;
    const CREATED_AT = null;
    protected $fillable = [
        'main_category_id',
        'sub_category',
    ];
    public function mainCategory()
    {
        // リレーションの定義
        // 追記
        return $this->belongsTo(MainCategory::class);
    }

    public function posts()
    {
        // リレーションの定義
    }
}
