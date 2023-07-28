<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    public function setReview(Product $product, Request $request) {

        $request->validate([
            'rating' => 'required|min:1|max:5',
            'comment' => 'nullable|string|max:500',
        ]);



        // check if the user has made a purchase for the product
        $order = Order::where('user_id', Auth::id())
                      ->whereHas('cartItems', function($query) use ($product) {
                          $query->where('product_id', $product->id);
                      })
                      ->first();

        if(!$order) {
            return response()->json(['error' => 'You must have purchased this product to leave a review.'], 403);
        }

        // check if the user has already left a review for this product
        $existing_review = Review::where('user_id', Auth::id())
                                 ->where('product_id', $product->id)
                                 ->first();

        // update the existing review
        if($existing_review) {
            $existing_review->rating = $request->rating;
            $existing_review->comment = $request->comment;
            $existing_review->save();
        } else { // create a new review
            $rating = $product->reviews()->create([
                'user_id' => Auth::id(),
                'rating' => $request->rating,
                'comment' => $request->comment,
            ]);
        }

        // update the product's average rating
        $averageRating= $product->calculateAverageRating();
        $product->average_rating = $averageRating;
        $product->save();

        return response()->json(['message' => 'Rating saved successfully.']);
    }

    public function showProductReviews($product_id)
    {
        // Retrieve the product by its ID
        $product = Product::findOrFail($product_id);

        // Retrieve all the reviews associated with the product, and select only the comment  data
        $reviews = $product->reviews()->select('comment','rating','user_id')->get();

        // Return the reviews data as a JSON response
        return response()->json($reviews);
    }

}
