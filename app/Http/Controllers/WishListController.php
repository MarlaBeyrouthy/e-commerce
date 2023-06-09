<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class WishListController extends Controller
{
    /*public function addToWishlist( $productId)
    {
        $user = auth()->user();
        $user->wishlists()->attach($productId);

        return response()->json(['message' => 'Product added to wishlist.']);
    }*/

    public function addToWishlist(Request $request)
    {
        $productId = $request->query('productId');
        $user = auth()->user();
        $user->wishlists()->attach($productId);

       // print_r($productId);
       // dd($request->all());
        return response()->json(['message' => 'Product added to wishlist.']);
    }


    public function removeFromWishlist(Request $request, $productId)
    {
        $user = auth()->user();
        $user->wishlists()->detach($productId);

        return response()->json(['message' => 'Product removed from wishlist.']);
    }



    public function getWishlist(Request $request)
    {
        $user = auth()->user();
        $wishlist = $user->wishlists()->get()->makeHidden(['pivot', 'updated_at','created_at','in_stock',
            'quantity','sizes','material','user_id','brand_name','gender','category','description']);

        return response()->json(['wishlist' => $wishlist]);
    }


}
