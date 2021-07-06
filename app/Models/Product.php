<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
  use HasFactory;
  public $timestamps = false;
	protected $fillable = [
    'productpcid',
    'productrecipeid',
    'productname',
    'productimg',
    'productdetail',
    'productprice',
    'productactive',
    'productcreatedat',
    'productcreatedby',
    'productmodifiedat',
    'productmodifiedby'
  ];
}