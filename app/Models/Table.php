<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Table extends Model
{
    public $timestamps = false;
    protected $primaryKey = 'tab_id';
    protected $table = 'tables';
    protected $fillable = [
        'tab_id',
        'tab_name',
        'tab_state',
    ];
    
    public function factura(){
        return $this->hasMany(Invoce::class);
    }
}
