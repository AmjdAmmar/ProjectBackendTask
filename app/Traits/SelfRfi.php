<?php
// app/Traits/UploadsImages.php

namespace App\Traits;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Category;


trait SelfRfi

{
    // public function parent(): BelongsTo
    // {
    //     return $this->belongsTo(Category::class, 'parent_id', 'id');
    // }

    // Define the relationship with child categories
    // public function children(): HasMany
    // {
    //     return $this->hasMany(Category::class, 'parent_id', 'id')->with('children');

    // }
    public function children(): HasMany
    {
        return $this->hasMany(Category::class, 'parent_id', 'id');
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'parent_id', 'id');
    }
    // public function getAllSubcategories()
    // {
    //     return $this->children()->with('getAllSubcategories')->get();
    // }

    // // Retrieve all categories with subcategories and related products
    // public static function getAllCategoriesWithProducts()
    // {
    //     return static::with('getAllSubcategories.products')->whereNull('parent_id')->get();
    // }

}
