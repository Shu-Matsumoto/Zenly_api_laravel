<?php

namespace Tests\Unit;

//use PHPUnit\Framework\TestCase;
use Tests\TestCase;

class EloquentTest extends TestCase
{
    /**
     * @test
     */
    public function IDを指定して１件取得()
    {
        $friend = \App\Eloquents\Friend::find(1);

        // dd($friend);

        $this->assertTrue(true);
    }

    /**
     * @test
     */
    public function 全件取得()
    {
        $friends = \App\Eloquents\Friend::all();

        //dd($friends);

        $this->assertTrue(true);
    }

    /**
     * @test
     */
    public function リレーションすげーよ()
    {
        // Friendが１のやつを取得
        $friend = \App\Eloquents\Friend::find(1);

        // EloquentのFriend.phpで設定したメソッド名でアクセス
        // たったこれだけで、FriendのID１に紐づく、FriendsRelationshipのデータが取得できる！！
        $relationships = $friend->relationship;

        // １対多の「多」を取得しているので、Collectionオブジェクトだからループできる
        $myFriendIds = [];
        foreach ($relationships as $myFriend) {
            $myFriendIds[] = $myFriend->other_friends_id; // 友だちIDだけを取得
        }

        // ddで見てみよう
        //dd($myFriendIds);

        $this->assertTrue(true);
    }

    /**
     * @test
     */
    public function Friend経由でPinの座標を取得()
    {
    }
}
