<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'description','user_id'];


    public function user(){
        return $this->belongsTo(User::class);
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
