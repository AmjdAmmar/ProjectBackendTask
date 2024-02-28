<?php

namespace App\Traits;


trait subcategory

{


    // protected function getCategoryData($category)
    // {
    //     $categoryInfo = [
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

    //         // Recursively get subcategories
    //         $subcategoryInfo['subcategories'] = $this->getCategoryData($subcategory);

    //         $categoryInfo['subcategories'][] = $subcategoryInfo;
    //     }

    //     return $categoryInfo;
    // }


    // protected function filterSubcategories($category)
    // {
    //     $filteredSubcategories = [];

    //     foreach ($category->children as $subcategory) {
    //         $filteredProducts = $subcategory->products->filter(function ($product) {
    //             return stripos($product->user->name, 'a') !== false && $product->price > 150;
    //         });

    //         if ($filteredProducts->isNotEmpty()) {
    //             $filteredSubcategories[] = [
    //                 'id' => $subcategory->id,
    //                 'name' => $subcategory->name,
    //                 'description' => $subcategory->description,
    //                 'status' => $subcategory->status,
    //                 'created_at' => $subcategory->created_at,
    //                 'updated_at' => $subcategory->updated_at,
    //                 'products' => $filteredProducts,
    //             ];
    //         }
    //     }

    //     return $filteredSubcategories;
    // }

    protected function getCategoryData($category)
{
    $filteredSubcategories = [];

    foreach ($category->children as $subcategory) {
        $filteredProducts = $subcategory->products->filter(function ($product) {
            return stripos($product->user->name, 'a') !== false && $product->price > 150;
        });

        $subcategoryInfo = [
            'id' => $subcategory->id,
            'name' => $subcategory->name,
            'description' => $subcategory->description,
            'status' => $subcategory->status,
            // 'created_at' => $subcategory->created_at,
            // 'updated_at' => $subcategory->updated_at,
            'products' => $filteredProducts,
            'subcategories' => [],
        ];

        $subcategoryInfo['subcategories'] = $this->getCategoryData($subcategory);

        $filteredSubcategories[] = $subcategoryInfo;
    }

    return $filteredSubcategories;
}

}
