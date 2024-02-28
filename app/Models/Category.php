<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use PhpParser\Builder\Function_;
use App\Traits\SelfRfi;

class Category extends Model
{
    use HasFactory;
    use SelfRfi;
    protected $guarded = ['id'];
    protected $fillable = [
        'name',
        'description',
        'status',
        'created_at',
        'parent_id',

    ];

    public function getCreatedFromAttribute()
    {
        return $this->created_at->diffForHumans();
    }

    // public static function tree()
    // {
    //     $allcategories = Category::get();
    //     $rootcategories = $allcategories->whereNull('parent_id');


    //     self::formatTree($rootcategories, $allcategories);





    //     return  $rootcategories;
    // }

    // public static function formatTree($categories, $allcategories){
    //     foreach ($allcategories as $category) {
    //         $category->children =    $allcategories->where('parent_id', $category->id)->values();

    //         $category->children =    $allcategories->where('parent_id', $category->id)->values();
    //         if($category->children->isNotEmpty()){
    //             self::formatTree($category->children,$allcategories );
    //         }
    //     }

    // }


    public function products(): HasMany
    {
        return $this->hasMany(Product::class, 'category_id', 'id');
    }

    public function image(): MorphOne
    {
        return $this->morphOne(Images::class, 'imageable');
    }
}
