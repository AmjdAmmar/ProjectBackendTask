<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Relations\Relation;
use App\Scopes\Priceproduct;

Relation::morphMap([
    'User' => 'app\Models\User',
    'Category' => 'app\Models\Category',
    'Product' => 'app\Models\Product',

]);
class Images extends Model
{
    use HasFactory;

    protected $fillable = [
        'imageable_id',
        'imageable_type',
        'url',
        'created_at',

    ];
    protected $attributes = [
        'url' => '', // Set a default value
    ];
    public function getCreatedFromAttribute()
    {
        return $this->created_at->diffForHumans();


    }

    public function imageable(): MorphTo
    {
        return $this->morphTo();
    }
    // protected static function booted()
    // {
    //     static::addGlobalScope(new Priceproduct);
    // }
}
