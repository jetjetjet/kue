<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class Menu extends Model
{
  use hasFactory;
  public $timestamps = false;
	protected $fillable = [
    'menumcid',
    'menuname',
    'menutype',
    'menuimg',
    'menudetail',
    'menuprice',
    'menuactive',
    'menuavaible',
    'menucreatedat',
    'menucreatedby',
    'menumodifiedat',
    'menumodifiedby'
  ];

  public static function getFields($model){
    $model->id = null;
    $model->menuname = null;
    $model->menuimg = null;
    $model->menuprice = null;
    $model->menucreatedat = null;
    $model->menucreatedby = null;
    $model->menumodifiedat = null;
    $model->menumodifiedby = null;

    return $model;
  }
}
