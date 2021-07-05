<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Showcase extends Model
{
  use HasFactory;
  public $timestamps = false;
	protected $fillable = [
    'showcaseproductid',
    'showcasecode',
    'showcaseqty',
    'showcasedate',
    'showcaseexpdate',
    'showcasestatus',
    'showcaseactive',
    'showcasecreatedat',
    'showcasecreatedby',
    'showcasemodifiedat',
    'showcasemodifiedby',
    'showcasesoldat',
    'showcasesoldby',
    'showcaseexpiredat',
    'showcaseexpiredby',
    'showcaseexpiredqty',
    'status'
  ];
}