<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use App\Models\Product;
use App\Models\Images;
use App\Scopes\PriceproductScope;
use Illuminate\Support\Facades\Hash;
class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'status',
        // 'password',

        'created_at',

    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];


    public function getCreatedFromAttribute()
    {
        return $this->created_at->diffForHumans();


    }

    public function product(): HasMany
    {
        return $this->hasMany(Product::class, 'user_id', 'id');
    }

    public function image(): MorphOne
    {
        return $this->morphOne(Images::class, 'imageable');
    }

    //    protected static function booted()
    // {
    //     parent::addGlobalScope(new PriceproductScope());
    // }
    // protected static function boot()
    // {
    //     parent::boot();

    //     static::creating(function ($user) {
    //         $user->password = Hash::make($user->password);
    //     });
    // }

}
