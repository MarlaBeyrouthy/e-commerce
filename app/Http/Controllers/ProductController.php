<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Product_Color;
use App\Models\ProductSize;
use App\Models\User;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    //create book -post
/*    public function createProduct(Request $request)
    {
        //Validate
        $request->validate([
            "product"=>"required",
            "description"=>"required",
            'brand_name',
            'price',
            'category_id',
            'color_id',
            //"seller_id" => "required",
            "user_id" => "nullable", // allow null values

        ]);

        //Create book data
        $product = new Product();

        $product->category_id = $request->category_id;
        $product->color_id = $request->color_id;

        // $product->user_id = $request->user_id; // pass value for user_id if available

        $product->seller_id=auth()->user()->id;
        $product->product= $request->product;
        $product->description= $request->description;
        $product->photo_product= $request->photo_product;
        $product->brand_name= $request->brand_name;
        $product->price= $request->price;


        $photo=$request->file('photo_product');
        if ($request->hasFile('photo_product'))
        {
            $extension=time(). '.' .$photo->getClientOriginalExtension();
            $photo->move(public_path('uploads/product_photo'),$extension);
            $extension='uploads/product_photo/'.$extension;

        }


        //save
        $product->save();

        //Send response
        return response()->json([
            "status"=>1,
            "message"=>"product created successfully"
        ]);


    }*/

    public function createProduct(Request $request)
    {
        // Validate
        $request->validate([
          //  dd($request->color_id),

            "product"=>"required",
            "description"=>"required",
            'brand_name',
            'price',
            'category_id',
            'color_id.*' => 'required',
           // 'color_id' => 'required|array',

            'size_ids.*' => 'required',
            "user_id" => "nullable", // allow null values
        ]);


        // Create product data
        $product = new Product();

        $photo = $request->file('photo_product');
        if ($request->hasFile('photo_product'))
        {
            $extension = time(). '.' .$photo->getClientOriginalExtension();
            $photo->move(public_path('uploads/product_photo'), $extension);
            $extension = 'uploads/product_photo/'.$extension;
        }

        // Save product
        $product->category_id = $request->category_id;
        $product->seller_id = auth()->user()->id;
        $product->product = $request->product;
        $product->description = $request->description;
      //  $product->photo_product = $extension;
        $product->brand_name = $request->brand_name;
        $product->price = $request->price;
        $product->save();


        $colorIds = is_array($request->color_id) ? $request->color_id : [$request->color_id];
        foreach ($colorIds as $color) {
            $store_color[] = [
                'product_id' => $product->id,
                'color_id' =>  $color,
            ];
        }

        Product_Color::insert($store_color);



        // Save product
        $product->save();

        $sizeIds = is_array($request->size_id) ? $request->size_id : [$request->size_id];
        foreach ($sizeIds as $size) {
            $productSize = new ProductSize();
            $productSize->product_id = $product->id;
            $productSize->size_id = $size;
            $productSize->save();
        }


        // Send response
        return response()->json([
            "status"=>1,
            "message"=>"product created successfully"
        ]);
    }



    //list book -get
    public function listProduct()
    {
        $product=Product::get();
        return response()->json([
            "status"=>1,
            "massage"=>"all products",
            "product"=>$product
        ]);
    }

    //author book -get
    public function sellerProduct()
    {
        $seller_id=auth()->user()->id;

        $product=Product::find($seller_id)->products;
        return response()->json([
            "status"=>1,
            "massage"=>"seller books",
            "product"=>$product
        ]);
    }

    //single book -get
    public function singleProduct($product_id)
    {
        $seller_id=auth()->user()->id;

        if(Product::where([
            "seller_id"=>$seller_id,
            "id"=>$product_id
        ])->exists()){
            $product = Product::find($product_id);

            return response()->json([
                "status"=>true,
                "message"=>"Product data found",
                "data"=>$product
            ]);
        }

        else{
            return response()->json([
                "status"=>false,
                "message"=>"Seller product doesnt exist"
            ]);
        }

    }

    //update book -post
    public function updateProduct(Request $request,$product_id)
    {
        $seller_id=auth()->user()->id;

        if(Product::where([
            "author_id"=>$seller_id,
            "id"=>$product_id
        ])->exists()) {
            $product = Product::find( $product_id );

            $product-> price      = isset( $request->price ) ? $request->price : $product->price;
            $product->description = isset( $request->description ) ? $request->description : $product->description;
            $product->brand_name   = isset( $request->brand_name ) ? $request->brand_name : $product->brand_name;
            $product-> product      = isset( $request->product ) ? $request->product : $product->product;


            $product->save();

            return response()->json( [
                "status"  => true,
                "message" => "product data update",
                "data"    => $product
            ] );
        }
        else {
            return response()->json( [
                "status"  => false,
                "message" => "User product does not exist"
            ] );
        }

    }


    //delete book -get
    public function deleteProduct($product_id)
    {
        $seller_id=auth()->user()->id;

        if(Product::where([
            "seller_id"=>$seller_id,
            "id"=>$product_id
        ])->exists()) {
            $product = Product::find( $product_id );

            $product->delete();
            return response()->json( [
                "status"  => true,
                "message" => "Product has been deleted",
            ] );

        }
        else {
            return response()->json( [
                "status"  => false,
                "message" => "Seller product does not exist"
            ] );
        }

    }
}
