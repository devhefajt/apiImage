<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Photo extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['name', 'description', 'notes', 'images'];

    // Enable automatic casting of deleted_at to a Carbon instance
    protected $dates = ['deleted_at'];

    protected $casts = [
        'images' => 'array', // Automatically handle JSON encoding/decoding
    ];
}




