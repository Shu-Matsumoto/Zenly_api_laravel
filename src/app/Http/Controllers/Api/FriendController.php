<?php

namespace App\Http\Controllers\Api;

use App\Eloquents\Friend;
use App\Eloquents\Friends_Relationship;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class FriendController extends Controller
{
    /**
     * @param \Illuminate\Http\Request $request
     * @param int $friendId
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Request $request, int $friendId)
    {
        // パスパラメータは第２引数以降で取得できる

        // Eloquentを利用してPinとともに取得
        $friend = Friend::with(['pin'])->find($friendId);

        // とりあえず、そのままレスポンスします（後ほど整形します）
        return response()->json($friend);
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function list(Request $request)
    {
        // Tokenから自分のIDを取得
        $myId = $request->user()->id;

        // Eloquentを利用して、自分の友だちのIDを取得
        $friendIds = Friends_Relationship::where('own_friends_id', $myId)
            ->get()
            ->pluck('other_friends_id')
            ->toArray();

        // 自分の友だちの情報を取得
        $friends = Friend::with(['pin'])
            ->whereIn('id', $friendIds)
            ->get();

        // 一発で取得するなら、こんな感じ
        // $friends = Friends_Relationship::with(['otherFriend.pin'])
        //     ->where('own_friends_id', $myId)
        //     ->get()
        //     ->pluck('otherFriend');

        return response()->json($friends);
    }
}
