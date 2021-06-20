<?php
namespace App\Repositories;

use App\Models\Order;
use App\Models\OrderDetail;
use Illuminate\Support\Facades\Log;
use DB;
use Carbon\Carbon;
use Exception;

class PurchaseRepository
{
  public static function getPurchase($respon, $id)
  {
    if($id){
      $data = Order::leftJoin('users as uvoid', 'uvoid.id', 'ordervoidedby')
        ->where('orderactive', '1')
        ->where('orders.id', $id)
        ->select(
          'orders.id',
          'orderinvoice',
          'ordertype',
          DB::raw("to_char(orderdate, 'dd/mm/yyyy HH24:MI') as orderdate"),
          DB::raw("to_char(orderpaidat, 'dd/mm/yyyy HH24:MI') as orderpaiddate"),
          'orderprice',
          'orderdp',
          'orderpaid',
          'orderpaidprice',
          'orderstatus',
          'orderdetail',
          'orderpaymentmethod',
          'ordervoidedat',
          'ordervoidreason',
          'ordervoidedby',
          'orderdiscountprice',
          'uvoid.username as ordervoidedusername',
          DB::raw("CASE WHEN orders.orderstatus = 'DP' THEN 'DP' WHEN orders.orderstatus = 'PAID' THEN 'Lunas' WHEN orders.orderstatus = 'VOIDED' THEN 'Batal' END as orderstatuscase")
        )->first();
      if($data == null){
        $respon['status'] = 'error';
        array_push($respon['messages'],'Pesanan tidak ditemukan!');
      } else {
        $data->items = self::getSubOrder($id);
      
        $respon['status'] = 'success';
        $respon['data'] = $data;
      }
    } else {
      $data = new \StdClass();
      $respon['data'] = self::dbOrderHeader($data);
    }
    return $respon;
  }

  public static function getSubOrder($idOrder)
  {
    $promo = DB::table('promo as p')
      ->join('subpromo as sp', 'sppromoid', 'p.id')
      ->where('spactive', '1')
      ->select(
        'p.id as promoid',
        'spproductid',
        'promodiscount'
      );

    return OrderDetail::join('products',function($q){
      $q->whereRaw("productactive = '1'")
        ->whereRaw("products.id = odproductid");})
      ->leftJoinSub($promo, 'promo', function ($join) {
        $join->on('products.id', '=', 'promo.spproductid');
        $join->on('odpromoid', '=', 'promoid');
      })
      ->where('odactive', '1')
      ->where('odorderid', $idOrder)
      ->select(
        'orderdetail.id',
        'odshowcaseid',
        'odproductid',
        'odtype',
        DB::raw("productname as odproducttext"),
        'odqty',
        'odprice',
        'odtotalprice',
        'odremark',
        'odindex',
        'odispromo',
        'odpromoid',
        'odpriceraw',
        'odtotalpriceraw',
        'promodiscount'
        )
      ->get();
  }

  public static function dbOrderHeader($model)
  {
    $model->id = null;
    $model->ordertype = null;
    $model->ordercustname = false;
    $model->orderdate = null;
    $model->orderprice = null;
    $model->orderdp = null;
    $model->orderstatus = null;
    $model->orderdetail = null;
    $model->orderpaymentmethod = null;
    $model->orderpaidprice = null;
    $model->orderdiscountprice = null;
    $model->orderpaidremark = null;
    $model->orderpaidby = null;
    $model->orderpaidat = null;
    $model->orderactive = null;
    $model->ordervoid = null;
    $model->ordervoidedby = null;
    $model->ordervoidedat = null;
    $model->ordervoidreason = null;
    $model->ordercreatedat = null;
    $model->ordercreatedby = null;
    $model->ordermodifiedat = null;
    $model->ordermodifiedby = null;
    $model->items = [];

    return $model;
  }
}