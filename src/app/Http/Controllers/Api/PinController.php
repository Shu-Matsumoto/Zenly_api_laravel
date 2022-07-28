<?php

namespace App\Http\Controllers\Api;

use App\Eloquents\Friend;
use App\Eloquents\Friends_Relationship;
use App\Eloquents\Pin;
use App\Http\Controllers\Controller;
use Facades\App\Contracts\Distance;
use Illuminate\Http\Request;

class PinController extends Controller
{
    /**
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $newFriends = \DB::transaction(function () use ($request) {
            // こんな風にアクセスしてきた人のIDを取得
            $myFriendId = $request->user()->id;

            // 自分のPinを削除
            Pin::where('friends_id', $myFriendId)->delete();

            // 自分のPinとして登録
            $myPin = new Pin;
            $myPin->fill([
                'friends_id' => $myFriendId,
                'latitude' => $request->input('latitude'),  // こんな風にリクエストデータを取得
                'longitude' => $request->input('longitude'),
            ]);
            $myPin->save();

            // すでに友達の人
            $myFriends = Friends_Relationship::where('own_friends_id', $myFriendId)->get();

            // まだ友達ではない人
            $notFriends = Friend::with(['pin']) // pin情報もこの後使うので、Eagerロード
                ->where('id', '<>', $myFriendId) // 自分以外
                ->whereNotIn('id', $myFriends->pluck('other_friends_id')->toArray()) // 既に友達の人は除外
                ->whereHas('pin', function ($query) {
                    // whereHasでPinを持っている人だけ
                    // かつ、追加クエリで、5分前より後にPinを打った人のみ
                    $query->where('created_at', '>=', now()->subMinutes(5));
                })
                ->get();

            // 近くのピンの人（友達になれそうな人）を探す
            // 実装は作ってるので、呼ぶだけ
            $canBeFriendIds = Distance::canBeFriends($myPin->toArray(), $notFriends->pluck('pin')->toArray());

            // 近くのピンの人がいれば友達になる
            foreach ($canBeFriendIds as $othersId) {
                // 自分の友達として登録
                $myRelation = new Friends_Relationship;
                $myRelation->fill([
                    'own_friends_id' => $myFriendId,
                    'other_friends_id' => $othersId,
                ]);
                $myRelation->save();

                // 相手の友達として登録
                $otherRelation = new Friends_Relationship;
                $otherRelation->fill([
                    'own_friends_id' => $othersId,
                    'other_friends_id' => $myFriendId,
                ]);
                $otherRelation->save();
            }

            // 新しく友達になった人
            return Friend::with(['pin'])
                ->whereIn('id', $canBeFriendIds)
                ->get();
        });

        // レスポンスはとりあえず、そのまま返す（後で成形する）
        return response()->json($newFriends);
    }
}
