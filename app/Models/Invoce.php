<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invoce extends Model
{
    public $timestamps = false;

    protected $primaryKey = 'inv_id';
    protected $fillable = [
        'inv_date_hour', 
        'inv_totalAmount', 
        'inv_tableId'
    ];

    public function table(){
        // return $this->belongsTo(Table::class, 'inv_id', 'tab_id');
        return $this->belongsTo(Table::class, 'inv_tableId', 'tab_id');
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'invoce_details','invDet_invoceId','invDet_proId');
    }
}
