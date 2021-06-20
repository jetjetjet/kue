<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;
    public $timestamps = false;
	protected $fillable = [
    'custname',
    'custaddress',
    'custphone',
    'custactive',
    'custcreatedat',
    'custcreatedby',
    'custmodifiedat',
    'custmodifiedby'
    ];

    public static function getFields($model){
        $model->id = null;
        $model->custname = null;
        $model->custaddress = null;
        $model->custphone = null;
        $model->custcreatedat = null;
        $model->custcreatedby = null;
        $model->custmodifiedat = null;
        $model->custmodifiedby = null;
    
        return $model;
      }
}
