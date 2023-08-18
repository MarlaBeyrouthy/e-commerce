<?php

namespace App\Http\Controllers;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserController;

use App\Models\report;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function showReports()
    {
        $reports = report::where('checked', 0)->get();

        return response()->json([
            'reports' => $reports,
        ]);
    }

    public function getUserReports($userId)
    {
        $reports = report::where('user_id',$userId)->get();
        if (!$reports) {
            return response()->json(['error' => 'not found'], 404);
        }
        return response()->json([
            'reports' => $reports,
        ]);
    }

    public function checkReports(Request $request)
    {
        $validatedData = $request->validate([
        'reports' => 'required|array|min:1',
        'reports.*' => 'exists:reports,id',
    ]);
        $ids=$validatedData['reports'];
        foreach ($ids as $id) {
            $report = report::find($id);
            $report->checked= 1;
            $report->save();
        }
    return response()->json(['message' => 'Product reports updated successfully']);
    }

    public function deleteProduct($id)
    {
    $product = Product::findOrFail($id);

    Storage::delete($product->photo);
    $product->delete();

    return response()->json(['message' => 'Product deleted successfully']);
    }

    public function deleteUser($id)
    {
    $User = User::findOrFail($id);
    if($User->permission_id==2){
        return response()->json(['message' => 'forbidden'],403);
    }
    $User->delete();

    return response()->json(['message' => 'User deleted successfully']);
    }

    public function BanUser($id)
    {
    $User = User::findOrFail($id);

    $User->products()->delete();
    Storage::delete('public/products_photos/'.$id);
    $User->permission_id =4;
    $User->save();

    return response()->json(['message' => 'User banned successfully']);
    }

//;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;
    public function unBanUser($id)
    {
    $User = User::findOrFail($id);
    if($User->permission_id!=4){
        return response()->json(['message' => 'the user is not banned from selling'],403);
    }
    $User->permission_id =1;
    $User->save();
    return response()->json(['message' => 'User unbanned successfully']);
    }

    public function getOrders()
    {
        $endDate = Carbon::now()->startOfDay(); // current time
        $startDate = Carbon::now()->subDays(15)->startOfDay(); // 15 days ago
        return $this->generateSalesReport($startDate, $endDate);
    }

    public function generateSalesReport($startDate, $endDate)
    {
        $orders = Order::whereBetween('created_at', [$startDate, $endDate])->get();

        $totalRevenue = 0;
        foreach ($orders as $order) {
            $totalRevenue += $order->total_price;
        }

        return response()->json([
            'start_date' => $startDate->format('Y-m-d H:i:s'),
            'end_date' => $endDate->format('Y-m-d H:i:s'),
            'total_revenue' => $totalRevenue,
            'orders' => $orders,
        ]);
    }

    public function searchProducts(Request $request)
    {
        $query = $request->input('s');

        $products = Product::where('name', 'like', "%$query%")
                                        ->orWhere('description', 'like', "%$query%")
                                        ->get();

        return response()->json(['products' => $products]);
    }

    public function searchUsers(Request $request)
    {
        $query = $request->input('s');

        $users = User::where('name', 'like', "%$query%")
                                        ->orWhere('email', 'like', "%$query%")
                                        ->get();

        return response()->json(['users' => $users]);
    }

    public function getUserOrders($userId)
    {
        $user = User::find($userId);
        if (!$user) {
            return response()->json(['error' => 'user not found'], 404);
        }

        // Retrieve the orders associated with this user
        $orders= $user->orders;

        return response()->json([
            'user' => $user,
        ]);
    }

    public function getSellers()
{
    $Sellers = User::has('products')->get()->makeHidden('password');
    return response()->json([
        'Sellers' => $Sellers,
    ]);
}
//;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;
public function getWorkers()
{
    $workers = User::where('permission_id', 3)->get();

    return response()->json([
        'workers' => $workers,
    ]);
}

public function showUser($userId)
{
    $user = User::findOrFail($userId)->makeHidden('password');
    $products = $user->products;
    return response()->json([
        'user' => $user,
    ]);
}

public function indexUsers()
{
    $users = User::all()->makeHidden('password');
    return response()->json([
        'users' => $users,
    ]);
}

public function showProduct($productId)
{
    $product = Product::with(['colors'])->findOrFail($productId);
    $product->sizes = json_decode($product->sizes, true);
    $product->color_names = $product->colors->pluck('color');
    $product = $product->makeHidden(['user','colors']);
    return response()->json([
        'product' => $product,
    ]);
}

public function indexProducts()
{
    $products = Product::all();

    return response()->json([
        'products' => $products,
    ]);
}

public function showOrder($orderId)
{
    $order = Order::findOrFail($orderId);

    $orderItems = $order->cartItems;

    return response()->json([
        'order' => $order,
        'orderItems' => $orderItems,
    ]);
}

public function indexOrders()
    {
        $orders =Order::all();

        return response()->json([
            'orders' => $orders,
        ]);
    }

    public function index_with_filter(Request $request)
    {
    $productController = new ProductController();
    $products = $productController->filters($request);
    return response()->json(['products' => $products]);
    }

}
