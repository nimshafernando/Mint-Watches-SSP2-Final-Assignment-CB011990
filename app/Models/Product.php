<?php

// Product.php (Model)

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'brand', 'price', 'stock', 'category', 'payment_status','description', 'images', 'image_path', 'user_id', 'status'
    ];

    protected $casts = [
        'images' => 'array', // Cast the images field to an array
    ];

    // Define the relationship to the User
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    
    public function reviews()
{
    return $this->hasMany(Review::class);
}

    public function payment()
{
    return $this->hasOne(Payment::class);
}
public function favorites()
    {
        return $this->hasMany(Favorite::class);
    }
}
