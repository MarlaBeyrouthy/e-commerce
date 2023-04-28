<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class WishListController extends Controller
{
    public function addToWishlist(Request $request, $productId)
    {
        $user = auth()->user();
        $user->wishlists()->attach($productId);

        return response()->json(['message' => 'Product added to wishlist.']);
    }

    public function removeFromWishlist(Request $request, $productId)
    {
        $user = auth()->user();
        $user->wishlists()->detach($productId);

        return response()->json(['message' => 'Product removed from wishlist.']);
    }

/*    public function getWishlist(Request $request)
    {
        $user = auth()->user();
        $wishlist = $user->wishlists;

        return response()->json(['wishlist' => $wishlist]);
    }*/

    public function getWishlist(Request $request)
    {
        $user = auth()->user();
        $wishlist = $user->wishlists()->get();

        return response()->json(['wishlist' => $wishlist]);
    }


}
