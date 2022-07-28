<?php

namespace App\Eloquents;

use Illuminate\Database\Eloquent\Model;

class Friends_Relationship extends Model
{
    //
    // 実際のテーブルが、クラス名の複数形＋スネークケースであれば、書かなくてOK
    protected $table = 'friends_relationships';

    // Eloquentを通して更新や登録が可能なフィールド（ホワイトリストを定義）
    protected $fillable = [
        'own_friends_id', 'other_friends_id'
    ];

    public function friend()
    {
        return $this->belongsTo(\App\Eloquents\Friend::class, 'own_friends_id', 'id');
    }
}
