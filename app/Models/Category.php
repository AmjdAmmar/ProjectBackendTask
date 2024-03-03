<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Traits\relationships; // Add this line to import the relationships trait

class Category extends Model
{
    use HasFactory;
    use relationships;


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





    public function products(): HasMany
    {
        return $this->hasMany(Product::class, 'category_id', 'id');
    }

    public function image(): MorphOne
    {
        return $this->morphOne(Images::class, 'imageable');
    }
}
