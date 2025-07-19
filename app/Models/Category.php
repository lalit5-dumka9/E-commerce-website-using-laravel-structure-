<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'image',
        'parentId',
        'slug',
        'extra',
        'status',
    ];

    public function getParentCategory()
    {
        return $this->belongsTo(Category::class,'parentId','id');
    }
}
