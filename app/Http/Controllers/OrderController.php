<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use App\Models\Color;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
class OrderController extends Controller
{

    public function placeOrder(Request $request)
    {
        // Retrieve the user ID from the token
        $userId = auth()->id();

        // Retrieve the cart items from the session
        $cart = Session::get('cart' . auth()->id(), []);

        // Calculate the total price of all cart items
        $totalPrice = 0;
        foreach ($cart as $cartItem) {
            $totalPrice += $cartItem['price'];

        }
       /* // Calculate the total price of all cart items
        $totalPrice = 0;
        foreach ($cart as $cartItem) {
            $totalPrice += $cartItem['price'] * $cartItem['quantity'];
        }*/

        // Create a new order object in the database
        $order = new Order([
            'order_date' => now(),
            'shipping_address' => $request->input('shipping_address'),
            'user_id' => $userId,
            'total_price' => $totalPrice,
        ]);
       // $order-> total_price->save();
        $order->total_price = $totalPrice;
        $order->save();

        //print_r($cart);

        // Create a new cart item object in the database for each item in the cart
        foreach ($cart as $cartItem) {
            $cartItemModel = new CartItem([
                'product_id' => $cartItem['product_id'],
                'name' => $cartItem['name'],
                'size' => json_decode($cartItem['size']), // Encode the size as JSON
                //'size' => $cartItem['size'],

                'quantity' => $cartItem['quantity'],
                'price' => $cartItem['price'],
            ]);

            // Set the size for the cart item
            $cartItemModel->size = $cartItem['size'];

           // print_r($cartItem['size']);
            // Associate the color with the cart item
            $color = Color::find($cartItem['color_id']);
            $cartItemModel->color()->associate($color);

            // Associate the order with the cart item
            $cartItemModel->order()->associate($order);

            $cartItemModel->save();

            // Update the product quantity
            $product = Product::find($cartItem['product_id']);
            $product->quantity -= $cartItem['quantity'];
            $product->save();
        }

        // Clear the cart items from the session
        Session::forget('cart' . auth()->id());

        // Return a response indicating success
        return response()->json([
            'message' => 'Order placed successfully'
        ]);
    }

    public function showOrder(Order $order)
    {
        $order = $order->load('cartItems.product', 'cartItems.color');
        return response()->json($order);
    }

    public function showAllOrder(User $user)
    {
        $orders = $user->orders()->with('cartItems.product', 'cartItems.color')->get();
       // $orders = $user->orders()->get();
        return response()->json($orders);
    }









}
