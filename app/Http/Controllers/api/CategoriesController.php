<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Images;

use Illuminate\Support\Facades\Validator;
use App\Http\Requests\CategoryRequest;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Traits\UploadsImages;
use App\Traits\subcategory;;

use Illuminate\Database\Eloquent\Builder;
use DataTables;
use \Illuminate\Support\Str;
use App\Models\Product;

class CategoriesController extends Controller
{
    use UploadsImages;
    use subcategory;


    // public function index()
    // {
    //     $categories = Category::with('children.products.user')->get();

    //     if ($categories->isEmpty()) {
    //         return response()->json(['error' => 'Categories not found'], 404);
    //     }

    //     $categoryData = [];

    //     foreach ($categories as $category) {
    //         $categoryInfo = [
    //             'id' => $category->id,
    //             'name' => $category->name,
    //             'description' => $category->description,
    //             'status' => $category->status,
    //             'created_at' => $category->created_at,
    //             'updated_at' => $category->updated_at,
    //             'subcategories' => [],
    //         ];

    //         foreach ($category->children as $subcategory) {
    //             $subcategoryInfo = [
    //                 'id' => $subcategory->id,
    //                 'name' => $subcategory->name,
    //                 'description' => $subcategory->description,
    //                 'status' => $subcategory->status,
    //                 'created_at' => $subcategory->created_at,
    //                 'updated_at' => $subcategory->updated_at,
    //                 'products' => $subcategory->products,
    //                 'subcategories' => [],

    //             ];





    //             $categoryInfo['subcategories'][] = $subcategoryInfo;


    //         }

    //         $categoryData[] = $categoryInfo;
    //     }

    //     return response()->json(['categories' => $categoryData]);
    // }
    // public function index()
    // {
    //     $categories = Category::with('children.products.user')->whereNull('parent_id')->get();

    //     if ($categories->isEmpty()) {
    //         return response()->json(['error' => 'Categories not found'], 404);
    //     }

    //     $categoryData = [];

    //     foreach ($categories as $category) {
    //         $categoryInfo = $this->getCategoryData($category);
    //         $categoryData[] = $categoryInfo;
    //     }

    //     return response()->json(['categories' => $categoryData]);
    // }


    public function index()
{
    $categories = Category::with('children.products.user')->whereNull('parent_id')->get();

    if ($categories->isEmpty()) {
        return response()->json(['error' => 'Categories not found'], 404);
    }

    $categoryData = [];

    foreach ($categories as $category) {
        $categoryInfo = $this->getCategoryData($category);
        $categoryData[] = $categoryInfo;
    }

    return response()->json(['categories' => $categoryData]);
}

    // public function index()
    // {
    //     $categories = Category::with('children.products.user')->whereNull('parent_id')->get();

    //     if ($categories->isEmpty()) {
    //         return response()->json(['error' => 'Categories not found'], 404);
    //     }

    //     $filteredCategories = [];

    //     foreach ($categories as $category) {
    //         $filteredSubcategories = $this->filterSubcategories($category);

    //         if (!empty($filteredSubcategories)) {
    //             $filteredCategories[] = [
    //                 'id' => $category->id,
    //                 'name' => $category->name,
    //                 'description' => $category->description,
    //                 'status' => $category->status,
    //                 'created_at' => $category->created_at,
    //                 'updated_at' => $category->updated_at,
    //                 'subcategories' => $filteredSubcategories,
    //             ];
    //         }
    //     }

    //     return response()->json(['categories' => $filteredCategories]);
    // }






    public function create()
    {
    }



    // public function store(CategoryRequest $request)
    // {
    //     // Validate the incoming request
    //     $validatedData = $request->validated();

    //     // Create a new category instance
    //     $category = new Category([
    //         'name' => $validatedData['name'],
    //         'description' => $validatedData['description'],
    //         'status' => $validatedData['status'],
    //     ]);

    //     // Save the category
    //     $category->save();

    //     // Upload images if they exist in the request
    //     if ($request->hasFile('images')) {
    //         $images = $request->file('images');
    //         $this->uploadImages($images, $category);
    //     }

    //     // Return a JSON response with success message and the created category
    //     return response()->json(['message' => 'Category created successfully', 'category' => $category], Response::HTTP_CREATED);
    // }




    public function store(CategoryRequest $request)
    {
        $validatedData = $request->validated();

        $existingCategory = Category::where('name', $validatedData['name'])->first();

        if ($existingCategory) {
            return response()->json(['message' => 'Category already exists'], Response::HTTP_CONFLICT);
        }

        $category = new Category($validatedData);
        $category->save();

        if ($request->hasFile('image1')) {
            $image = $request->file('image1');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('images'), $imageName);

            $imageLink = asset('images/' . $imageName);

            $mergedImage = new Images([
                'imageable_type' => Category::class,
                'imageable_id' => $category->id,
                'image1' => $imageLink,
            ]);
            $mergedImage->save();
        }

        return response()->json(['message' => 'Category created successfully', 'category' => $category, 'image1_link' => $imageLink ?? null], Response::HTTP_CREATED);
    }




    // public function show($id)
    // {
    //     $categories = Category::with('products.user')->find($id);

    //     if (!$categories) {
    //         return response()->json(['error' => 'Category not found'], 404);
    //     }

    //     $filteredProducts = $categories->products->filter(function ($product) {
    //         return stripos($product->user->name, 'a') !== false && $product->price >= 150;
    //     });
    //     $CategoriesArray = [];
    //     foreach ($filteredProducts as $category) {
    //         $CategoriesArray[] = [
    //             'category' =>  $filteredProducts,
    //             'image1' => $category->image,
    //         ];
    //     }

    //     return response()->json(['filtered_products' => $filteredProducts, 'created_from' => $categories->created_from]);
    // }


    // public function show($id)
    // {
    //     $categories = Category::with('children.products', 'children.products.user')->find($id);

    //     if ($categories->isEmpty()) {
    //         return response()->json(['error' => 'Categories not found'], 404);
    //     }

    //     $categoryData = [];

    //     foreach ($categories as $category) {
    //         $categoryInfo = [
    //             'id' => $category->id,
    //             'name' => $category->name,
    //             'description' => $category->description,
    //             'status' => $category->status,
    //             'created_at' => $category->created_at,
    //             'updated_at' => $category->updated_at,
    //             'subcategories' => [],
    //         ];

    //         foreach ($category->children as $subcategory) {
    //             $subcategoryInfo = [
    //                 'id' => $subcategory->id,
    //                 'name' => $subcategory->name,
    //                 'description' => $subcategory->description,
    //                 'status' => $subcategory->status,
    //                 'created_at' => $subcategory->created_at,
    //                 'updated_at' => $subcategory->updated_at,
    //                 'products' => $subcategory->products,
    //             ];

    //             $categoryInfo['subcategories'][] = $subcategoryInfo;
    //         }

    //         $categoryData[] = $categoryInfo;
    //     }

    //     return response()->json(['categories' => $categoryData]);
    // }

    //     public function show($id)
    // {
    //     $category = Category::with('children.products.user')->find($id);

    //     if (!$category) {
    //         return response()->json(['error' => 'Category not found'], 404);
    //     }

    //     $categoryData = [
    //         'id' => $category->id,
    //         'name' => $category->name,
    //         'description' => $category->description,
    //         'status' => $category->status,
    //         'created_at' => $category->created_at,
    //         'updated_at' => $category->updated_at,
    //         'subcategories' => [],
    //     ];

    //     foreach ($category->children as $subcategory) {
    //         $subcategoryInfo = [
    //             'id' => $subcategory->id,
    //             'name' => $subcategory->name,
    //             'description' => $subcategory->description,
    //             'status' => $subcategory->status,
    //             'created_at' => $subcategory->created_at,
    //             'updated_at' => $subcategory->updated_at,
    //             'products' => $subcategory->products,
    //         ];

    //         $categoryData['subcategories'][] = $subcategoryInfo;
    //     }

    //     return response()->json(['category' => $categoryData]);
    // }

    public function show($id)
    {
        $category = Category::with('children.products.user')->find($id);

        if (!$category) {
            return response()->json(['error' => 'Category not found'], 404);
        }

        $categoryData = $this->getCategoryData($category);

        return response()->json(['category' => $categoryData]);
    }

    // public function show($id)
    // {
    //     $category = Category::with('children.products.user')->find($id);

    //     if (!$category) {
    //         return response()->json(['error' => 'Category not found'], 404);
    //     }

    //     $filteredSubcategories = $this->filterSubcategories($category);

    //     $categoryData = [
    //         'id' => $category->id,
    //         'name' => $category->name,
    //         'description' => $category->description,
    //         'status' => $category->status,
    //         'created_at' => $category->created_at,
    //         'updated_at' => $category->updated_at,
    //         'subcategories' => $filteredSubcategories,
    //     ];

    //     return response()->json(['category' => $categoryData]);
    // }






    public function edit($id)
    {
        //
    }


    // public function update(CategoryRequest $request, $id)
    // {
    //     $Category = Category::findOrFail($id);

    //     $Category->name = $request->name;
    //     $Category->description = $request->description;
    //     $Category->status = $request->status;

    //     $Category->save();


    //     if ($request->hasFile('images')) {
    //         $images = $request->file('images');
    //         $this->uploadImages($images, $Category);
    //     }



    //     // return response()->json(['message' => 'Category update successfully', 'product' => $Category, 'mergedImage' => $this->uploadImage($image1, $image2, $Category)], Response::HTTP_CREATED);
    //     return response()->json(['message' => 'Product update successfully', 'product' => $Category, 'mergedImage' => $this->uploadImages($images, $Category)], Response::HTTP_CREATED);
    // }


    // public function update(CategoryRequest $request, $id)
    // {
    //     $category = Category::findOrFail($id);

    //     $category->fill($request->only(['name', 'description', 'status']));

    //     $category->save();

    //     if ($request->hasFile('images')) {
    //         $images = $request->file('images');
    //         $this->uploadImages($images, $category);
    //     }

    //     return response()->json(['message' => 'Category updated successfully', 'category' => $category], Response::HTTP_OK);
    // }

    public function update(CategoryRequest $request, $id)
{
    $validatedData = $request->validated();

    $category = Category::findOrFail($id);

    // Update category attributes
    $category->update($validatedData);

    if ($request->hasFile('image1')) {
        $image = $request->file('image1');
        $imageName = time() . '.' . $image->getClientOriginalExtension();
        $image->move(public_path('images'), $imageName);

        $imageLink = asset('images/' . $imageName);

        $existingImage = $category->image()->first();

        if ($existingImage) {
            $existingImage->update(['image1' => $imageLink]);
        } else {
            $mergedImage = new Images([
                'imageable_type' => Category::class,
                'imageable_id' => $category->id,
                'image1' => $imageLink,
            ]);
            $mergedImage->save();
        }
    }

    return response()->json(['message' => 'Category updated successfully', 'category' => $category, 'image1_link' => $imageLink ?? null], Response::HTTP_OK);
}

    public function destroy($id)
    {
        $category = Category::findOrFail($id);
        $category->delete();

        return response()->json(['message' => 'Category deleted successfully']);
    }
}
