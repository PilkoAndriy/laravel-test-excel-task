<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    public $timestamps = false;

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];


    public function products()
    {
        return $this->hasMany('App\Models\Product');
    }

    public function parent()
    {
        return $this->belongsTo('App\Models\Category','parent_id')->with('parent');
    }
}
