<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SecondSubCategory extends Model
{
     protected $fillable = ['sub_category_id', 'name', 'image'];

    public function subcategory()
    {
        return $this->belongsTo(SubCategory::class);
    }
    public function thirdsubcategories()
    {
        return $this->hasMany(ThirdSubCategory::class);
    }
}
