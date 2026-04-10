<?php

namespace App\Models;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubCategory extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'description','category_id'];

    public function category(){
        return $this->belongsTo(Category::class);
    }

    public function product(){
        return $this->hasMany(Product::class);
    }


    public function getTitleAttribute($value)
    {
        return ucwords($value);
    }
     public function getDescriptionAttribute($value)
    {
        return ucfirst($value);
    }
}
