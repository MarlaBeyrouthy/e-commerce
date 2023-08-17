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

/*    public function addToWishlist(Request $request)
    {
        $productId = $request->query('productId');
        $user = auth()->user();
        $user->wishlists()->attach($productId);

       // print_r($productId);
       // dd($request->all());
        return response()->json(['message' => 'Product added to wishlist.']);
    }*/

    public function addToWishlist(Request $request)
    {
        $productId = $request->query('productId');
        $user = auth()->user();

        // Check if the product is already in the user's wishlist
        if ($user->wishlists()->where('product_id', $productId)->exists()) {
            return response()->json(['message' => 'Product is already in the wishlist.'], 400);
        }

        // Attach the product to the wishlist
        $user->wishlists()->attach($productId);

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

    public function getIDs()
    {
        // Assuming your user_favorite_persons table has 'user_id' and 'favorite_person_id' columns
        $user = auth()->user();
        $favoriteProductIds = $user->wishlists()->pluck('product_id');

        return response()->json([
            'favorite_product_ids' => $favoriteProductIds
        ]);
    }

}
