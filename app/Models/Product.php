<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory;
    use SoftDeletes;

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    public function brand()
    {
        return $this->belongsTo('App\Models\Brand');
    }

    public function category()
    {
        return $this->belongsTo('App\Models\Category')->with('parent');
    }

    public function scopeInStock($query)
    {
        return $query->where('in_stock', 1);
    }

    public function scopeBrand($query, $brand_id)
    {
        return $query->where('brand_id', $brand_id);
    }

    public function scopeCategory($query, $category_id)
    {
        return $query->where('category_id', $category_id);
    }
}
