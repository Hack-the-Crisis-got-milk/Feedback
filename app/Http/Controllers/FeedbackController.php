<?php

namespace App\Http\Controllers;

use App\Feedback;
use Illuminate\Http\Request;

class FeedbackController extends Controller
{
    public function provideFeedback(Request $request)
    {
        Feedback::create(
            [
                'type' => $request->input('type'),
                'item_group_id' => $request->input('item_group_id'),
                'value' => $request->input('value'),
                'shop_id' => $request->input('shop_id')
            ]
        );

        return response(['message' => 'created']);
    }

    public function getFeedbackForShops(Request $request)
    {
        $shopIds = json_decode($request->input('shopIds'), true);
        $feedBacks = [];

        foreach ($shopIds as $shopId) {
            $feedBacks[] = Feedback::where('shop_id', $shopId)->get();
        }

        return response(['feedbacks' => $feedBacks]);
    }
}
