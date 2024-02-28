<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Models\product;
use App\Http\Requests\ProductRequest;
use App\Traits\UploadsImages;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Images;
use Carbon\Carbon;

class productsController extends Controller
{
    use UploadsImages;


    public function index()
    {

        $products = Product::where('price', '>=', 150)->get();

        $productsArray = [];
        foreach ($products as $product) {
            $productsArray[] = [
                'id' => $product->id,
                'name' => $product->name,
                'category_id' => $product->category_id,
                'user_id' => $product->user_id,
                'description' => $product->description,
                'price' => $product->price,
                'status' => $product->status,
                'image1' => $product->image,
                'created_from' => $product->created_from,
            ];

        }
        // dd($productsArray);

        return response()->json(['products' => $productsArray]);
        //   // Loop through each product and access the created_from attribute//     //   echo 'Product: ' . $product->name . ' - Created ' . $product->created_from . '<br>
    }


    public function create()
    {
        //
    }

    public function store(ProductRequest $request)
    {
        $validatedData = $request->validated();

        $existingProduct = Product::where('name', $validatedData['name'])
            ->where('description', $validatedData['description'])
            ->where('price', $validatedData['price'])
            ->where('quantity', $validatedData['quantity'])
            ->where('status', $validatedData['status'])
            ->first();

        if ($existingProduct) {
            return response()->json(['message' => 'Product already exists'], Response::HTTP_CONFLICT);
        }

        $product = new Product($validatedData);

        $product->save();

        $path1 = $request->file('image1')->store('public/images');
        $path2 = $request->file('image2')->store('public/images');

        $mergedImage = new Images([
            'imageable_type' => Product::class,
            'imageable_id' => $product->id,
            'image1' => $path1,
            'image2' => $path2,
        ]);
        $mergedImage->save();

        return response()->json(['message' => 'Product created successfully', 'product' => $product, 'mergedImage' => $mergedImage], Response::HTTP_CREATED);
    }

    public function show($id)
    {
        $products = Product::where('price', '>=', 150)->findOrFail($id);
        $productArray = [
            'id' => $products->id,
            'name' => $products->name,
            'category_id' => $products->category_id,
            'user_id' => $products->user_id,
            'description' => $products->description,
            'price' => $products->price,
            'status' => $products->status,
            'image1' => $products->image,
            'created_from' => $products->created_from,
        ];


        return response()->json(['products' => $productArray]);
    }

    public function edit($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $product->name = $request->name;
        $product->description = $request->description;
        $product->category_id = $request->category_id;
        $product->user_id = $request->user_id;
        $product->price = $request->price;
        $product->quantity = $request->quantity;
        $product->status = $request->status;

        $product->save();
        // if ($request->hasFile('image1') && $request->hasFile('image2')) {
        //     $image1 = $request->file('image1');
        //     $image2 = $request->file('image2');
        //     $this->uploadImage($image1, $image2, $product);
        // }
        // return response()->json(['message' => 'Product update successfully', 'product' => $product, 'mergedImage' => $this->uploadImage($image1, $image2, $product)], Response::HTTP_CREATED);
        if ($request->hasFile('images')) {
            $images = $request->file('images');
            $this->uploadImages($images, $product);
        }



        // return response()->json(['message' => 'Category update successfully', 'product' => $Category, 'mergedImage' => $this->uploadImage($image1, $image2, $Category)], Response::HTTP_CREATED);
        return response()->json(['message' => 'Product update successfully', 'product' => $product, 'mergedImage' => $this->uploadImages($images, $product)], Response::HTTP_CREATED);
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        $product->delete();

        return response()->json(['message' => 'Product deleted successfully']);
    }
}
