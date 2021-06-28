<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
	use HasFactory;
	
  public $timestamps = false;
	protected $fillable = [
    'orderinvoice',
    'orderinvoiceindex',
    'ordertype',
    'ordercustname',
    'orderdate',
    'orderprice',
		'orderdp',
    'orderstatus',
		'orderdetail',
		'orderpaymentmethod',
		'orderpaid',
		'orderpaidprice',
		'orderpaidremark',
		'orderpaidby',
		'orderpaidat',
		'orderactive',
		'ordervoid',
		'ordervoidedby',
		'ordervoidedat',
		'ordervoidreason',
		'ordercreatedat',
		'ordercreatedby',
		'ordermodifiedat',
		'ordermodifiedby',
		'orderdiscountprice',
    'orderestdate',
    'ordercompleteddate',
    'ordercompletedby',
    'orderremainingpaid'
  ];
}
