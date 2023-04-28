<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Color;
use App\Models\Size;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{


    public function create(Request $request)
    {
        // Define an array of valid categories
        $valid_Categories =
            [ 'shoes', 'pants', 'shorts', 'watches',
                'bags', 'accessories', 'sport wears', 'jackets', 'hats', 'dress'];
        $valid_Genders = ['men', 'women', 'kids'];

        // Validate the request data
        $validatedData = $request->validate([
            'name' => 'required',
            'description' => 'nullable',
            'price' => 'required|numeric|min:0',
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
        $product->category = $validatedData['category'];
        $product->gender = $validatedData['gender'];
        $product->brand_name = $validatedData['brand_name'];
        $product->material = $validatedData['material'];
        // set the user id to the authenticated user's id
        $product->user_id = auth()->id();
        $filename = time() . '.' . $request->photo->getClientOriginalExtension();
        $request->photo->storeAs('public/product_photos', $filename);
        $product->photo = 'public/product_photos/'.$filename;

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
        $product->sizes = json_decode($product->sizes, true);
        $product = $product->makeHidden(['user']);
        $product->colors->makeHidden('pivot');
        /*
        //checking if this product is mine
        $my_product=false;
        if (auth()->user()->id == $product->user_id) {
            $my_product=true;
        }
        $product->my_product = $my_product;
 */
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
            $product = $product->makeHidden(['user', 'user_id','colors','created_at','updated_at']);
        }
        return response()->json(['products' => $products]);
    }



    public function user_products($id)
    {
        $query = Product::where('user_id',$id)->with([ 'colors'])->get();
        $products = $query->all();
        //don't worry if you see an errors because it's normal and it's working
        foreach ($products as $product) {
            $product->sizes = json_decode($product->sizes, true);
            $product->color_names = $product->colors->pluck('color');
            $product = $product->makeHidden([ 'user_id','colors','created_at','updated_at']);
        }
        return response()->json(['products' => $products]);
    }



    public function my_products()
    {
        $id = auth()->id();
        $products =$this->user_products($id);

        return $products;
    }



    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        if (auth()->user()->id !== $product->user_id) {
            return response()->json(['massage' => 'forbidden'],403);
        }
        // Define an array of valid categories
        $valid_Categories =
            [ 'shoes', 'pants', 'shorts', 'watches',
                'bags', 'accessories', 'sport wears', 'jackets', 'hats', 'dress'];
        $valid_Genders = ['men', 'women', 'kids'];

        // Validate the request data
        $validatedData = $request->validate([
            'name' => 'required',
            'description' => 'nullable',
            'price' => 'nullable|numeric|min:0',
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
            $filename = time() . '.' . $request->photo->getClientOriginalExtension();
            $request->photo->storeAs('public/product_photos', $filename);
            $validatedData['photo'] = 'public/product_photos/'.$filename;
        }

        $product->update($validatedData);

        return response()->json(['message' => 'Product updated successfully', 'product' => $product]);
    }



    public function change_status(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        if (auth()->user()->id !== $product->user_id) {
             return response()->json(['massage' => 'forbidden'],403);
        }
        $status = $request->validate([
            'in_stock' => 'required|boolean'
        ]);

        $product->update($status);

        return response()->json(['message' => 'Product status updated successfully']);
    }






    public function delete($id)
    {
        $product = Product::findOrFail($id);

        if (auth()->user()->id !== $product->user_id) {
             return response()->json(['massage' => 'forbidden'],403);
        }

        Storage::delete($product->photo);
        $product->delete();

        return response()->json(['message' => 'Product deleted successfully']);
    }



    public function filters(Request $request)
    {
        $valid_Genders = ['men', 'women', 'kids'];
        $valid_Categories =
            ['shoes', 'pants', 'shorts', 'watches',
                'bags', 'accessories', 'sport wears', 'jackets', 'hats', 'dress'];

        // Validate the request data
        $validatedData = $request->validate([
            'gender' => 'nullable|in:' . implode(',', $valid_Genders),
            'category' => 'nullable|in:' . implode(',', $valid_Categories),
            'color_id' => 'nullable|exists:colors,id',
        ]);


        $query = Product::with(['colors','user']);


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
            $product = $product->makeHidden(['user','colors','created_at','updated_at']);
        }
        return response()->json(['products' => $products]);
    }


}






