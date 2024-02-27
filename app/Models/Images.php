<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Images extends Model
{
    use HasFactory;

    protected $fillable = [
        // 'url',
        'imageable_id',
        'imageable_type',
        'image1',
        'image2',
        'created_at',

    ];
    public function getCreatedFromAttribute()
    {
        return $this->created_at->diffForHumans();
       

    }

    public function imageable(): MorphTo
    {
        return $this->morphTo();
    }
}
