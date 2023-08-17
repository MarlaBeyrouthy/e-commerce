<?php

namespace App\Http\Controllers;

use App\Events\ProductQuantityEmpty;
use App\Mail\OrderChecked;
use App\Mail\OrderPlaced;
use App\Models\CartItem;
use App\Models\City;
use App\Models\Color;
use App\Models\Order;
use App\Models\Place;
use App\Models\Product;
use App\Models\User;
use App\Notifications\ProductQuantityEmptyNotification;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Broadcast;
use App\Events\NewOrder;
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

        // Create a new order object in the database
        $order = new Order([
            'order_date' => now(),
            'shipping_address' => $request->input('shipping_address'),
            'city_id' => $request->input('city_id'),
            'place_id' => $request->input('place_id'),
            'user_id' => $userId,
            'total_price' => $totalPrice,
        ]);


        $order->city_id = $request->city_id;
        $order->place_id =$request->place_id;
        $order->total_price = $totalPrice;


        $order->save();
       // print_r($order);
      //  broadcast(new NewOrder($order))->toOthers();
        // Retrieve the product owner for each cart item
       /* foreach ($cart as $cartItem) {
            $product = Product::find($cartItem['product_id']);
            $productOwner = $product->user;

            // Use the $productOwner variable as needed
            // For example, you can pass it to the NewOrder event
            broadcast(new NewOrder($order, $productOwner))->toOthers();
        }*/

       // broadcast(new NewOrder($order, $productOwner))->toOthers();

        //broadcast(new NewOrder($order, $productOwner))->toOthers();

       // event(new NewOrder($order));


        //print_r($cart);

        // Retrieve the city and place information for the order

        $city = City::find($request->input('city_id'));
        $place = Place::find($request->input('place_id'));

        // Retrieve the user information for the email
        $user = User::find($userId);


        // Send email to users with permission_id = 3
        $workers = User::where('permission_id', 3)->get();
        foreach ($workers as $worker) {
            $data = [
                'order_id'=>$order->id,
                'order_date' => $order->order_date,
                'total_price' => $order->total_price,
                'shipping_address' => $order->shipping_address,
                'user_name' => $user->name,
                'user_phone' => $user->phone,
                'city_name' => $city->city,
                'place_name' => $place->place,

            ];

          //  Mail::to($worker->email)->send(new OrderPlaced($data));
        }

        // Create a new cart item object in the database for each item in the cart
        foreach ($cart as $cartItem) {
            $cartItemModel = new CartItem([
                'product_id' => $cartItem['product_id'],
                'name' => $cartItem['name'],
                'size' => json_decode($cartItem['size']), // Encode the size as JSON
                'quantity' => $cartItem['quantity'],
                'price' => $cartItem['price'],
            ]);

            // Set the size for the cart item
            $cartItemModel->size = $cartItem['size'];

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
            $productOwner = $product->user;
            $userId = $productOwner->id;
            $productName = $product->name; // Make sure you have this line
            $quantity = $cartItem['quantity']; // Make sure you have this line

            event(new NewOrder($order, $productOwner, $quantity, $productName));

            $message=  'Product quantity has reached zero: ' . $productName;

            if ($product->quantity <= 0) {
                $seller = $product->user; // Assuming you have a relationship set up

              // $seller->notify(new ProductQuantityEmptyNotification($message, $userId));

                event(new ProductQuantityEmpty($message, $userId));

            }

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

    public function checkOrder($id)
    {
        // Find the order by ID
        $order = Order::findOrFail($id);

        // Check if the authenticated user has permission_id = 3
        $user = Auth::user();
        if ($user->permission_id != 3) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        // Update the 'checked' status
        $order->checked = true;
        $order->save();

        // Retrieve the user who made the purchase
        $customer = $order->user;

        // Send email to the customer
        Mail::to($customer->email)->send(new OrderChecked($order));

        // Return a response indicating success
        return response()->json(['message' => 'Order checked successfully']);
    }


}
