<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Brands;
use App\Models\Category;
class Brands extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'image',
        'category',
        'slug',
        'extra',
        'status',
    ];
    public function getCategory()
    {
        return $this->belongsTo(Category::class,'category','id');
    }
}
