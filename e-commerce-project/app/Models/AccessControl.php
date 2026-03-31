<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccessControl extends Model
{
    use HasFactory;

    protected $fillable = ['granted_by', 'granted_to', 'target_user'];

    public function giver()
    {
        return $this->belongsTo(User::class, 'granted_by');
    }

    public function receiver()
    {
        return $this->belongsTo(User::class, 'granted_by');
    }

    public function target()
    {
        return $this->belongsTo(User::class, 'granted_by');
    }
}
