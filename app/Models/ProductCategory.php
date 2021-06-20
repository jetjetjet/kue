<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductCategory extends Model
{
  use HasFactory;
  protected $table = 'productcategories';
  public $timestamps = false;
	protected $fillable = [
    'pcname',
    'pcactive',
    'pccreatedat',
    'pccreatedby',
    'pcmodifiedat',
    'pcmodifiedby'
  ];
}