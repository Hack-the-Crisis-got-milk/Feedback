<?php

namespace App\Http\Controllers;

use App\Device;
use App\Wishlist;
use Illuminate\Http\Request;

class WishlistController extends Controller
{
    public function addToWishlist(Request $request)
    {
        Wishlist::create(
            [
                'item_group_id' => $request->input('item_group_id')
            ]
        );

        return response(['message' => 'created']);
    }
}
