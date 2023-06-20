<?php

namespace App\Models;

use App\Http\Controllers\products\ProductController;
use App\Models\Order;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $table='products';
    protected $fillable=['product_name','desc','price'];
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function orders()
    {
        return $this->belongsToMany(Order::class);
    }
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }


}
