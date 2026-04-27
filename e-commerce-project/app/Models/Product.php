<?php

namespace App\Models;

use App\Models\Category;
use App\Models\ProductImage;
use App\Models\SubCategory;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Number;

class Product extends Model
{
    use HasFactory;
    protected $fillable = ['title', 'description', 'price', 'quantity', 'user_id', 'category_id', 'subCategory_id', 'review_status', 'admin_message', 'is_read'];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function subCategory()
    {
        return $this->belongsTo(SubCategory::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function getTitleAttribute($value)
    {
        return ucwords($value);
    }
    public function getDescriptionAttribute($value)
    {
        return ucfirst($value);
    }
    public function getPriceAttribute($value)
    {
        return Number::currency($value, in: 'USD');
    }
}
