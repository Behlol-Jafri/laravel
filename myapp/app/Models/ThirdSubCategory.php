<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ThirdSubCategory extends Model
{
    protected $fillable = ['second_sub_category_id', 'name', 'image'];

    public function secondsubcategory()
    {
        return $this->belongsTo(SecondSubCategory::class);
    }
}
