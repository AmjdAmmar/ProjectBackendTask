<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Models\product;
use App\Http\Requests\Product\Product_updateRequest;
use App\Http\Requests\Product\Product_storeRequest;

use App\Traits\UploadsImages;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Images;
use Carbon\Carbon;

class productsController extends Controller
{



    public function index()
    {
        $products = Product::with('user', 'images')->get();

        $productsArray = [];
        foreach ($products as $product) {
            $imageUrls = [];

            $images = $product->images;

            foreach ($images as $image) {
                $imageUrls[] = asset($image->image);
            }

            $productsArray[] = [
                'id' => $product->id,
                'name' => $product->name,
                'category_id' => $product->category_id,
                'user' => [
                    'id' => $product->user->id,
                    'name' => $product->user->name,
                    'email' => $product->user->email,
                ],
                'description' => $product->description,
                'price' => $product->price,
                'status' => $product->status,
                'images' => $imageUrls,
                'created_from' => $product->created_from,
            ];
        }

        return response()->json(['products' => $productsArray]);
    }
    public function create()
    {
        //
    }

    public function store(Product_storeRequest $request)
    {
        $validatedData = $request->validated();

        $existingProduct = Product::where('name', $validatedData['name'])->first();

        if ($existingProduct) {
            return response()->json(['message' => 'Product already exists'], Response::HTTP_CONFLICT);
        }

        $product = Product::create($validatedData);

        // dd(  $product);
        if ($request->hasFile('images')) {
            $imageLinks = [];

            foreach ($request->file('images') as $image) {
                $imageName = time() . '_' . $image->getClientOriginalName();
                $image->move(public_path('images/product'), $imageName);
                $imageLink = asset('images/product/' . $imageName);
                $imageLinks[] = $imageLink;
                $product->images()->create(['url' => $imageLink]);
            }

            return response()->json([
                'message' => 'Product created successfully',
                'product' => $product,
                'image_links' => $imageLinks
            ], 201);
        }

        return response()->json(['message' => 'Product created successfully', 'product' => $product], 201);
    }


    public function show($id)
    {
        $product = Product::findOrFail($id);

        $imageUrls = [];

        $images = $product->images;

        foreach ($images as $image) {
            $imageUrls[] = asset($image->image); // Assuming your image model has a 'image' attribute containing the image URL
        }

        $productArray = [
            'id' => $product->id,
            'name' => $product->name,
            'category_id' => $product->category_id,
            'user_id' => $product->user_id,
            'description' => $product->description,
            'price' => $product->price,
            'status' => $product->status,
            'user' => [
                'id' => $product->user->id,
                'name' => $product->user->name,
                'email' => $product->user->email,
            ],
            'images' => $imageUrls, // Include the array of image URLs
            'created_from' => $product->created_from,
        ];

        return response()->json(['product' => $productArray]);
    }

    public function edit($id)
    {
        //
    }


    public function update(Product_updateRequest $request, $id)
    {
        $validatedData = $request->validated();

        $product = Product::findOrFail($id);

        $product->update($validatedData);

        // $imageLinks = [];

        if ($request->hasFile('images')) {
            $imageLinks = [];
            foreach ($request->file('images') as $image) {
                $imageName = time() . '_' . $image->getClientOriginalName();
                $image->move(public_path('images/product'), $imageName);

                $imageLink = asset('images/product/' . $imageName);
                $imageLinks[] = $imageLink;

                if ($product->images->count() > 0) {
                    $product->images()->update(['url' => $imageLink]);
                } else {
                    $product->images()->create(['url' => $imageLink]);
                }
            }
        }

        return response()->json([
            'message' => 'Product updated successfully',
            'product' => $product,
            'image_links' => $imageLinks
        ], Response::HTTP_OK);
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        $product->delete();

        return response()->json(['message' => 'Product deleted successfully']);
    }
}
