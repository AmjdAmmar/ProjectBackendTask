<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Images;

use Illuminate\Support\Facades\Validator;
use App\Http\Requests\Category\Category_storeRequest;
use App\Http\Requests\Category\Category_updateRequest;

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
    use subcategory;




    public function index()
    {
        $categories = Category::with('children.products.user.image')->whereNull('parent_id')->get();
        if ($categories->isEmpty()) {
            return response()->json(['error' => 'Categories not found'], 404);
        }
        $categoryData = [];

        foreach ($categories as $category) {
            $categoryInfo = $this->getCategoryData($category);
            $categoryData[] = $categoryInfo;
        }
        // $categoryData = Category::with('image')->get();
        return response()->json(['categories' => $categoryData]);
    }


    public function create()
    {
    }


    public function store(Category_storeRequest $request)
    {
        $validatedData = $request->validated();

        $category = Category::create($validatedData);

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->move(public_path('images/category'), $imageName);
            $imageLink = asset('images/category' . $imageName);

            $category->image()->create(['url' => $imageLink]);

            return response()->json(['message' => 'Category created successfully', 'category' => $category, 'image_link' => $imageLink], Response::HTTP_CREATED);
        }

        return response()->json(['message' => 'Category created successfully', 'category' => $category], Response::HTTP_CREATED);
    }



    public function show($id)
    {
        $category = Category::with('children.products.user.image')->find($id);

        if (!$category) {
            return response()->json(['error' => 'Category not found'], 404);
        }

        $categoryData = $this->getCategoryData($category);

        return response()->json(['category' => $categoryData]);
    }



    public function edit($id)
    {
        //
    }



    public function update(Category_updateRequest $request, $id)
    {
        $validatedData = $request->validated();

        $category = Category::findOrFail($id);

        $category->update($validatedData);

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('images/category'), $imageName);

            $imageLink = asset('images/category' . $imageName);

            if ($category->image) {
                $category->image->update(['image' => $imageLink]);
            } else {
                $category->image()->create(['image' => $imageLink]);
            }
        }

        return response()->json(['message' => 'Category updated successfully', 'category' => $category, 'image_link' => $imageLink ?? null], Response::HTTP_OK);
    }

    public function destroy($id)
    {
        $category = Category::findOrFail($id);
        $category->delete();

        return response()->json(['message' => 'Category deleted successfully']);
    }
}
