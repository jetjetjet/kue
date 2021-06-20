<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderDetail extends Model
{
  use HasFactory;
  protected $table = 'orderdetail';
  public $timestamps = false;
	protected $fillable = [
    'odorderid',
    'odproductid',
    'odshowcaseid',
    'odtype',
    'odqty',
    'odprice',
    'odtotalprice',
    'odremark',
    'odindex',
    'oddelivered',
    'odactive',
    'odcreatedat',
    'odcreatedby',
    'odmodifiedat',
    'odmodifiedby',
    'odispromo',
    'odpromoid',
    'odtotalpriceraw',
    'odpriceraw'
  ];
}