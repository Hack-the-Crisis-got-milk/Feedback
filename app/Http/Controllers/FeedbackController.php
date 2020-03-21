<?php

namespace App\Http\Controllers;

use App\Feedback;
use Illuminate\Http\Request;

class FeedbackController extends Controller
{
    const GET_ITEM_GROUPS_URL = 'localhost:8010/api/v1/itemgroups/';

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
        $client = new \GuzzleHttp\Client();
        $shopIds = json_decode($request->input('shopIds'), true);
        $feedBacks = [];
        $response = $client->request('GET', self::GET_ITEM_GROUPS_URL);
        $items = json_decode($response->getBody(), true)['item_groups'];
        foreach ($shopIds as $shopId) {
            $feedBacks[$shopId][] = Feedback::where('shop_id', $shopId)
                ->where('type', 'busyness')
                ->orderBy('created_at', 'desc')
                ->take(1)
                ->get()[0];
            foreach ($items as $item) {
                $feedBacks[$shopId][] = Feedback::where('shop_id', $shopId)
                    ->where('type', 'availability')
                    ->where('item_group_id', $item['id'])
                    ->orderBy('created_at', 'desc')
                    ->take(1)
                    ->get()[0];
            }
        }

        return response(['response' => $feedBacks]);
    }
}
