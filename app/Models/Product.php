<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    public $timestamps = false;
    protected $primaryKey = 'pro_id';
    protected $fillable = ['pro_id','pro_name','pro_price','pro_categoryID'];

    public function categorias(){
        return $this->belongsTo(Category::class,'pro_id','cat_id');
    }

    public function invoces(){
        return $this->belongsToMany(Invoce::class, 'invoce_details','invDet_proId','invDet_invoceId');
    }
}