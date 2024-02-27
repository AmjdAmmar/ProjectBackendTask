<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Carbon\Carbon;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'user_id',
        'name',
        'description',
        'quantity',
        'price',
        'status',
        'created_at',
    ];

    public function getCreatedFromAttribute()
    {
        return $this->created_at->diffForHumans();
       

    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function image(): MorphMany
    {
        return $this->morphMany(Images::class, 'imageable');
    }
}