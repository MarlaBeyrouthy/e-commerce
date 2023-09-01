<?php

namespace App\Http\Controllers;

use App\Events\ProductQuantityEmpty;
use App\Events\ProductSaleChanged;
use App\Models\Product;
use App\Models\Color;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{

    public function create(Request $request)
    {
        // Define an array of valid categories
        $valid_Categories =
        [ 'shoes' , 'shirts' , 'pants', 'shorts', 'watches',
         'bags', 'accessories', 'sport wears', 'jackets', 'hats', 'dress'];
        $valid_Genders = ['men', 'women', 'boys','girls'];

        // Validate the request data
        $validatedData = $request->validate([
            'name' => 'required',
            'description' => 'nullable',
            'price' => 'required|numeric|min:0',
            'quantity' => 'required|integer|min:1',
            'category' => 'required|in:' . implode(',', $valid_Categories),
            'gender' => 'required|in:' . implode(',', $valid_Genders),
            'brand_name' => 'nullable',
            'material' => 'nullable',
            'photo' => 'required|image|mimes:jpeg,png,jpg',
            'sizes' => 'required|array|min:1',
            'colors' => 'required|array|min:1',
            'colors.*' => 'exists:colors,id',
        ]);

        $product = new Product;
        $product->name = $validatedData['name'];
        $product->description = $validatedData['description'];
        $product->price = $validatedData['price'];
        $product->quantity = $validatedData['quantity'];
        $product->category = $validatedData['category'];
        $product->gender = $validatedData['gender'];
        $product->brand_name = $validatedData['brand_name'];
        $product->material = $validatedData['material'];
        // set the user id to the authenticated user's id
        $product->user_id = auth()->id();
        if(auth()->user()->permission_id!=1){
            return response()->json(['message' => 'forbidden'],403);
        }
        $filename = time(). '.' . $request->photo->getClientOriginalExtension();
        $request->photo->storeAs('public/products_photos/'.$product->user_id, $filename);
        $product->photo = 'products_photos/'.$product->user_id.'/'.$filename;
        //$product->photo = 'public/product_photos'.$filename;

        $sizes = json_encode($validatedData['sizes']);
        $product->sizes = $sizes;

        $product->save();
        $product->colors()->sync($validatedData['colors']);
        /*
                foreach ($validatedData['sizes'] as $sizeName) {
                    $size = new Size;
                    $size->size = $sizeName;
                    $product->sizes()->save($size);
                }
                foreach ($validatedData['colors'] as $colorName) {
                    $color = new Color;
                    $color->color = $colorName;
                    $product->colors()->save($color);
                }
         */
        $product->sizes = json_decode($product->sizes, true);
        // Return a response indicating success
        return response()->json(['message' => 'Product created successfully', 'product' => $product], 201);
    }

    public function show($id)
    {
        $product = Product::with([ 'colors','user'])->findOrFail($id);
        //don't worry if you see an errors because it's normal and it's working
        $product->user_name = $product->user->name;
        $product->user_photo = $product->user->photo;
        $product->sizes = json_decode($product->sizes, true);
        //$product->url = Storage::url($product->photo);
        $product->color_names = $product->colors->pluck('color');
        $product = $product->makeHidden(['user','colors']);
        $product->colors->makeHidden('pivot');

        //checking if this product is mine
        $my_product=false;
        if (auth()->user()->id == $product->user_id) {
            $my_product=true;
        }
        $product->my_product = $my_product;

        return response()->json($product);
    }

    public function search(Request $request)
    {

    $request->validate([
        'name' => 'required',]);
    $name=$request->input('name');
    $products= Product::where('name','like','%'.$name.'%')->get();
    foreach ($products as $product) {
        $product->sizes = json_decode($product->sizes, true);
    }
        return Product::where('name','like','%'.$name.'%')->get();
    }

    public function index()
    {
    $query = Product::with([ 'colors','user'])->get();
    $products = $query->all();
    //don't worry if you see an errors because it's normal and it's working
    foreach ($products as $product) {
        $product->user_name = $product->user->name;
        $product->sizes = json_decode($product->sizes, true);
        $product->color_names = $product->colors->pluck('color');
        $product = $product->makeHidden(['user','colors']);
    }
    return response()->json(['products' => $products]);
    }

    public function index_with_filter(Request $request)
    {
        $products =$this->filters($request);
        return response()->json(['products' => $products]);
    }

    public function my_products(Request $request)
    {
        $request->request->set('user_id', auth()->id());
        $products =$this->filters($request);

    return response()->json(['products' => $products]);
    }

    public function update(Request $request, $id)
    {
    $product = Product::findOrFail($id);

    if (auth()->user()->id !== $product->user_id) {
             return response()->json(['message' => 'forbidden'],403);
    }
        // Define an array of valid categories
        $valid_Categories =
        [ 'shoes' , 'shirts' , 'pants', 'shorts', 'watches',
         'bags', 'accessories', 'sport wears', 'jackets', 'hats', 'dress'];
        $valid_Genders = ['men', 'women', 'boys','girls'];

        // Validate the request data
        $validatedData = $request->validate([
            'name' => 'required',
            'description' => 'nullable',
            'price' => 'nullable|numeric|min:0',
            'quantity' => 'nullable|integer|min:0',
            'category' => 'nullable|in:' . implode(',', $valid_Categories),
            'gender' => 'nullable|in:' . implode(',', $valid_Genders),
            'brand_name' => 'nullable',
            'material' => 'nullable',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg',
            'sizes' => 'nullable|array|min:1',
            'colors' => 'nullable|array|min:1',
            'colors.*' => 'exists:colors,id',
        ]);


    if($request->has('colors')){
        $product->colors()->sync($validatedData['colors']);
    }

    if($request->hasFile('photo')){
        Storage::delete($product->photo);
        $filename = time(). '.' . $request->photo->getClientOriginalExtension();
        $request->photo->storeAs('public/products_photos/'.$product->user_id, $filename);
        $validatedData['photo'] = 'products_photos/'.$product->user_id.'/'.$filename;
        //$validatedData['photo'] = 'public/product_photos'.$filename;
    }

    if($request->has('quantity')){
    if($validatedData['quantity']==0){
        $validatedData['in_stock'] = 0;
    }
    else{
        $validatedData['in_stock'] = 1;
    }
    }

    $product->update($validatedData);

    return response()->json(['message' => 'Product updated successfully', 'product' => $product]);
    }

    public function change_status(Request $request)
    {
        $validatedData = $request->validate([
        'products' => 'required|array|min:1',
        'products.*' => 'exists:products,id',
        'in_stock' => 'required|boolean'
    ]);
        $ids=$validatedData['products'];
        foreach ($ids as $id) {
            $product = Product::find($id);
            if (auth()->user()->id !== $product->user_id) {
                return response()->json(['message' => 'forbidden'],403);
           }
           $product->in_stock= $validatedData['in_stock'];
           $product->save();
        }
    return response()->json(['message' => 'Product status updated successfully']);
    }

    public function change_prices(Request $request)
    {
        $validatedData = $request->validate([
        'products' => 'required|array|min:1',
        'products.*' => 'exists:products,id',
        'increase_prices' => 'required|numeric|min:0|max:100',
     ]);
        $ids=$validatedData['products'];
        foreach ($ids as $id) {
            $product = Product::find($id);
            if (auth()->user()->id !== $product->user_id) {
                return response()->json(['message' => 'forbidden'],403);
           }
           $product->price= $product->price*(100+$validatedData['increase_prices'])/100;
           $product->save();
        }
    return response()->json(['message' => 'Products prices updated successfully']);
    }

    public function delete($id)
    {
    $product = Product::findOrFail($id);

    if (auth()->user()->id !== $product->user_id) {
         return response()->json(['message' => 'forbidden'],403);
    }

    Storage::delete($product->photo);
    $product->delete();

    return response()->json(['message' => 'Product deleted successfully']);
    }

    public function filters(Request $request)
    {
        $valid_Genders = ['men', 'women', 'boys','girls'];
        $valid_Categories =
            ['shoes' , 'shirts' , 'pants', 'shorts', 'watches',
             'bags', 'accessories', 'sport wears', 'jackets', 'hats', 'dress'];

        // Validate the request data
        $validatedData = $request->validate([
            'gender' => 'nullable|in:' . implode(',', $valid_Genders),
            'category' => 'nullable|in:' . implode(',', $valid_Categories),
            'color_id' => 'nullable|exists:colors,id',
            'user_id' => 'nullable|exists:users,id',
        ]);


        $query = Product::with(['colors','user']);


        if ($request->has('user_id')) {
            $query->where('user_id', $validatedData['user_id']);
        }
        if ($request->has('gender')) {
            $query->where('gender', $validatedData['gender']);
        }
        if ($request->has('category')) {
            $query->where('category', $validatedData['category']);
        }

        if ($request->has('color_id')) {
            $query->whereHas('colors', function ($query) use ($validatedData) {
                $query->where('color_id', $validatedData['color_id']);
            });
        }

        $products = $query->get();
        //don't worry if you see an errors because it's normal and it's working
        foreach ($products as $product) {
            $product->user_name = $product->user->name;
            $product->sizes = json_decode($product->sizes, true);
            $product->color_names = $product->colors->pluck('color');
            $product = $product->makeHidden(['user','colors',]);
        }
        return  $products;
    }

//;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;
    public function setSales(Request $request)
    {
        $validatedData = $request->validate([
        'products' => 'required|array|min:1',
        'products.*' => 'exists:products,id',
        'sale' => 'required|integer|min:0|max:100',
     ]);
        $ids=$validatedData['products'];
        foreach ($ids as $id) {
            $product = Product::find( $id );
            if ( auth()->user()->id !== $product->user_id ) {
                return response()->json( [ 'message' => 'forbidden' ], 403 );
            }

            $oldSale       = $product->sale;
            $product->sale = $validatedData['sale'];
            $product->save();

            $wishlistUsers = DB::table( 'wishlists' )
                               ->where( 'product_id', $product->id )
                               ->pluck( 'user_id' )
                               ->toArray();
            $message=  'Hurry up! new sale: ' . $product->sale. '% for the product: ' .$product->name;

            foreach ( $wishlistUsers as $userId ) {
                $user = User::find( $userId );
                if ( $user ) {
                    event(new ProductSaleChanged($message, $userId));
                }
            }
        }

            return response()->json(['message' => 'Products sales updated successfully']);
    }


/////////////////////////////////////////////////////////   for Noore only
//;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;
public function putphoto(Request $request)
{
    $request->validate([
        'photo' => 'required|image|mimes:jpg',
    ]);

    $filename = 'noore.jpg';
    $photo = $request->photo->storeAs('public/test_photos', $filename);

    $url = Storage::url($photo);
     return response()->json(['photo' => $url]);
}
//;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;
public function showphoto()
{
    $url = Storage::url('public/test_photos/noore.jpg');
     return response()->json(['photo' => $url]);
}

}
