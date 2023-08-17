<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Support\Facades\Session;
class CartItemController extends Controller
{
    public function AddToCart(Request $request)
    {

        $validatedData = $request->validate([
            'product_id' => 'required|exists:products,id',
            'color_id' => 'required|exists:colors,id',
            'size'=> 'required|string',
            'quantity' => 'required|integer|min:1',
        ]);
       // print_r($validatedData['size']);
        $product = Product::find($validatedData['product_id']);
        $price = $product->price * $validatedData['quantity'] * (1 - ($product->sale / 100));


        if (!$product->colors()->where('color_id', $validatedData['color_id'])->exists()) {
            return response()->json([
                'message' => 'incorrect color'],422);
        }

        $product_sizes = json_decode($product->sizes, true);

        //searching if the size we requested if it is in the product array
        if (!in_array($validatedData['size'], $product_sizes)) {
            return response()->json([
                'message' => 'incorrect size'],422);
        }

        if ($product->in_stock==0) {
            return response()->json([
                'message' => 'the product is out of stock'],422);
        }
        if ($product->quantity<$validatedData['quantity']) {
            return response()->json([
                'message' => 'sorry you cannot order that much'],422);
        }

        // Retrieve the cart from the session and add the new item
        $cart = Session::get('cart'.auth()->id(), []);
        $index = count($cart);
        $cartItem = [
            'index' => $index,
            'name' => $product->name,
            'product_id' => $validatedData['product_id'],
            'quantity' => $validatedData['quantity'],
            'color_id' => $validatedData['color_id'],
            'size' => $validatedData['size'],
            'photo' => $product->photo,
            'price' => $price
        ];
        $cart[$index] = $cartItem;
        Session::put('cart'.auth()->id(), $cart);

        // Return a response indicating success
        return response()->json([
            'message' => 'Cart item added successfully',
            'cart_item' => array_merge($cartItem, ['index' => $index]),]);
    }

    public function ShowCart()
    {
        // Get the cart from the session
        $total=0;
        $cart = Session::get('cart'.auth()->id(), []);
        foreach ($cart as $cartItem) {
            $total+=$cartItem['price'];
        }
        // Return a response with the cart items
        return response()->json([
            'cart_items' => $cart,
            'total_price' => $total,]);
    }

    public function ShowCartItem($index)
    {
        // Get the cart from the session
        $cart = Session::get('cart'.auth()->id(), []);

        if (!isset($cart[$index])) {
            return response()->json([
                'message' => 'Invalid cart item index',
            ], 400);
        }
        // Return a response with the cart items
        return response()->json([
            'cart_item' => $cart[$index],]);
    }

    public function ChangeDetails(Request $request, $index)
{
    $validatedData = $request->validate([
        'color_id' => 'required|exists:colors,id',
        'size'=> 'required|string',
        'quantity' => 'required|integer|min:1',
    ]);

    // Get the cart from the session
    $cart = Session::get('cart'.auth()->id(), []);

        // Check if the index is valid
        if (!isset($cart[$index])) {
            return response()->json([
                'message' => 'Invalid cart item index',
            ], 400);
        }


        $product = Product::find($cart[$index]['product_id']);
        $price = $product->price * $validatedData['quantity'] * (1 - ($product->sale / 100));

        if (!$product->colors()->where('color_id', $validatedData['color_id'])->exists()) {
            return response()->json([
                'message' => 'incorrect color'],422);
        }

    $product_sizes = json_decode($product->sizes, true);
    //searching if the size we requested if it is in the product array
    if (!in_array($validatedData['size'], $product_sizes)) {
        return response()->json([
            'message' => 'incorrect size'],422);
    }

    if ($product->quantity<$validatedData['quantity']) {
        return response()->json([
            'message' => 'sorry you cannot order that much'],422);
    }
    // Update the cart item with the new quantity
    $cart[$index]['color_id'] = $validatedData['color_id'];
    $cart[$index]['size'] = $validatedData['size'];
    $cart[$index]['quantity'] = $validatedData['quantity'];
    $cart[$index]['price'] = $price;

        // Save the updated cart to the session
        Session::put('cart'.auth()->id(), $cart);

        // Return a response indicating success
        return response()->json([
            'message' => 'Cart item updated successfully',
            'cart_item' => $cart[$index],]);
    }

    public function DeleteCartItem($index)
    {
        // Get the cart from the session
        $cart = Session::get('cart'.auth()->id(), []);

        // Check if the index is valid
        if (!isset($cart[$index])) {
            return response()->json([
                'message' => 'Invalid cart item index',
            ], 400);
        }

        // Remove the cart item at the specified index
        unset($cart[$index]);

        // Save the updated cart to the session
        Session::put('cart'.auth()->id(), $cart);

        // Return a response indicating success
        return response()->json([
            'message' => 'Cart item deleted successfully',]);
    }

    //
}






