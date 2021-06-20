<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;
    public $timestamps = false;
	protected $fillable = [
    'settingcategory',
    'settingkey',
    'settingvalue',
    'settingactive',
    'settingcreatedat',
    'settingcreatedby',
    'settingmodifiedat',
    'settingmodifiedby'
    ];

    public static function getFields($model){
        $model->id = null;
        $model->settingcategory = null;
        $model->settingkey = null;
        $model->settingvalue = null;
        $model->settingcreatedat = null;
        $model->settingcreatedby = null;
        $model->settingmodifiedat = null;
        $model->settingmodifiedby = null;
    
        return $model;
      }
}
