<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Promo extends Model
{
	use HasFactory;
	
  protected $table = 'promo';
  public $timestamps = false;
	protected $fillable = [
    'promoname',
    'promodetail',
    'promostart',
    'promoend',
    'promodiscount',
    'promoactive',
    'promocreatedat',
    'promocreatedby',
    'promomodifiedat',
    'promomodifiedby'
  ];
}