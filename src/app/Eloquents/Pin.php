<?php

namespace App\Eloquents;

use Illuminate\Database\Eloquent\Model;

class Pin extends Model
{
    //
    // 実際のテーブルが、クラス名の複数形＋スネークケースであれば、書かなくてOK
    protected $table = 'pins';

    // Eloquentを通して更新や登録が可能なフィールド（ホワイトリストを定義）
    protected $fillable = [
        'friends_id', 'latitude', 'longitude'
    ];

    public function friend()
    {
        return $this->hasOne(\App\Eloquents\Friend::class, 'id', 'friends_id');
    }
}
