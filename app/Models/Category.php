<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    public $timestamps = false;
    protected $table = 'categories';
    protected $primaryKey = 'cat_id';
    protected $fillable = ['cat_id','cat_name'];

    public function products()
    {
        return $this->hasMany(Product::class, 'pro_categoryID', 'cat_id');
    }
}
