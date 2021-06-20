<?php
namespace App\Repositories;

use App\Models\Order;
use App\Models\Menu;
use App\Models\User;
use App\Models\Expense;
use DB;

class ReportRepository
{
  public static function grid($inputs)
  {
    $od = Order::where('orderactive', '1')
      ->whereRaw("orderdate::date between '" . $inputs['startdate'] . "' and '" . $inputs['enddate'] . "'")
      ->join('users', 'ordercreatedby', '=', 'users.id');
    if($inputs['user'] != 'Semua'){
      $od->where('users.id', $inputs['user']);
    }
    if($inputs['status'] == 'Diproses'){
      $od->whereNotIn('orderstatus', ['PAID', 'VOIDED']);
    }elseif($inputs['status'] != 'Semua'){
      $od->where('orderstatus', $inputs['status']);
    }elseif($inputs['status'] == 'Semua'){
      $od->whereNotIn('orderstatus', ['VOIDED']);
    }
    $data = $od->select(
      'orders.id',
      DB::raw("to_char(orderdate, 'DD-MM-YYYY') as tanggal"),       
      DB::raw("CASE WHEN orders.ordertype = 'DINEIN' THEN 'Makan ditempat' ELSE 'Bungkus' END as ordertypetext"), 
      'orderinvoice',
      'orderdiscountprice',
      DB::raw("orderprice - coalesce(orderdiscountprice,0) as price"),
      DB::raw("CASE WHEN orders.orderstatus = 'PAID' THEN 'Lunas' WHEN orders.orderstatus = 'VOIDED' THEN 'Dibatalkan' ELSE 'Diproses' END as orderstatuscase"),
      'username',
      )
    ->get();
    return $data;
  }
  
  public static function get($inputs)
  {
    $od = Order::where('orderactive', '1')
      ->whereRaw("orderdate::date between '" . $inputs['startdate'] . "' and '" . $inputs['enddate'] . "'")
      ->join('users', 'ordercreatedby', '=', 'users.id');
    if($inputs['user'] != 'Semua'){
      $od->where('users.id', $inputs['user']);
    }
    if($inputs['status'] == 'Diproses'){
      $od->whereNotIn('orderstatus', ['PAID', 'VOIDED']);
    }elseif($inputs['status'] != 'Semua'){
      $od->where('orderstatus', $inputs['status']);
    }elseif($inputs['status'] == 'Semua'){
      $od->whereNotIn('orderstatus', ['VOIDED']);
    }
    $data = $od->select(
      DB::raw("sum(orderprice) - sum(coalesce(orderdiscountprice,0)) as total"),
      )
      ->first();

    return $data;
  }

  public static function gridEx($inputs)
  {
    $ex = Expense::where('expenseactive', '1')
    ->join('users as cr', 'cr.id', '=', 'expensecreatedby' )
    ->leftJoin('users as er', 'er.id', '=', 'expenseexecutedby')
    ->whereRaw("expensedate::date between '" . $inputs['startdate'] . "' and '" . $inputs['enddate'] . "'");
    if($inputs['status'] == '0'){
      $ex->where('expenseexecutedby', '0');
    }elseif($inputs['status'] == '1'){
      $ex->whereNotIn('expenseexecutedby', ['0']);
    }
    $data = $ex->select(
          'expenses.id',
          'expensename', 
          'expensedetail', 
          'expenseprice',
          DB::raw("to_char(expensedate, 'DD-MM-YYYY') as tanggal"),
          DB::raw("CASE WHEN expenses.expenseexecutedby = '0' THEN 'Draft' ELSE 'Selesai' END as status"),
          'cr.username as create',
          'er.username as execute',
          DB::raw("to_char(expenseexecutedat, 'DD-MM-YYYY') as tanggalend"),
        )
        ->get();

    return $data;
  }

  public static function getEx($inputs){
    $ex = Expense::where('expenseactive', '1')
      ->whereRaw("expensedate::date between '" . $inputs['startdate'] . "' and '" . $inputs['enddate'] . "'");
      if($inputs['status'] == '0'){
        $ex->where('expenseexecutedby', '0');
      }elseif($inputs['status'] == '1'){
        $ex->whereNotIn('expenseexecutedby', ['0']);
      }
    $data = $ex->select(DB::raw("sum(expenseprice) as total"))->first();

    return $data;
  }

  public static function getName()
  {
    return User::where('useractive', '1')
      ->select('id', 'username')
      ->get();
  }

  public static function getShiftReport($filter)
  {
    $q = DB::table('shifts as s')
      ->join('orders as o', DB::Raw('ordercreatedat::date'), '=' ,DB::Raw('shiftstart::date'))
      ->join('users as u', 'u.id','shiftcreatedby')
      ->where('shiftactive', '1')
      ->where('orderactive', '1')
      ->whereRaw("shiftcreatedat::date between '". $filter['startdate'] . "'::date and '" . $filter['enddate'] . "'::date")
      ->groupBy(DB::raw("shiftcreatedby, shiftcreatedat::date, username, orderstatus, shiftstartcash, shiftendcash, shiftstartcoin, shiftendcoin"))
      ->orderBy('shiftcreatedat', 'DESC');
    
    if($filter['status'] == "PAID"){
      $q = $q->where('orderstatus', 'PAID')
      ->whereBetween(DB::raw('orderpaidat::timestamp'), [DB::raw('shiftstart::timestamp'), DB::raw('shiftclose::timestamp')]);
    } else if($filter['status'] == 'INPROG'){
      $q = $q->where('orderstatus', 'ADDITIONAL')
        ->orWhere('orderstatus', 'PROCEED')
        ->whereBetween(DB::raw('ordermodifiedat::timestamp'), [DB::raw('shiftstart::timestamp'), DB::raw('shiftclose::timestamp')]);
    } else if($filter['status'] == "VOIDED"){
      $q = $q->where('orderstatus', 'VOIDED')
      ->whereBetween(DB::raw('ordervoidedat::timestamp'), [DB::raw('shiftstart::timestamp'), DB::raw('shiftclose::timestamp')]);
    } else{
      $q = $q->where('orderstatus', ['PAID', 'ADDITIONAL', 'PROCEED'])
      ->whereBetween(DB::raw('ordermodifiedat::timestamp'), [DB::raw('shiftstart::timestamp'), DB::raw('shiftclose::timestamp')]);
    }

    if($filter['user'] != "ALL"){
      $q = $q->where('shiftcreatedby', $filter['user']);
    }
    $getRow = $q->select(
      'shiftcreatedby',
			'username',
			DB::raw("s.shiftcreatedat::date"),
      DB::raw("coalesce(shiftstartcash,0) as kertasawal"),
      DB::raw("coalesce(shiftstartcoin,0) as koinawal"),
      DB::raw("coalesce(shiftstartcash,0) + coalesce(shiftstartcoin,0) as totalstart"),
      DB::raw("coalesce(shiftendcash,0) as kertasakhir"),
      DB::raw("coalesce(shiftendcoin,0) as koinakhir"),
      DB::raw("coalesce(shiftendcash,0) + coalesce(shiftendcoin,0) as totalakhir"),
      DB::raw("(coalesce(shiftendcash,0) + coalesce(shiftendcoin,0)) - (coalesce(shiftstartcash,0) + coalesce(shiftstartcoin,0)) as selisih"),
      DB::raw("sum(orderprice) - sum(coalesce(orderdiscountprice,0)) as totalorder")
    )->get();

    $data = new \StdClass();
    $data->data = $getRow;
    return $data;
  }

  public static function getMenuReport($inputs)
  {
    $detailOrder = DB::table('orderdetail')
      ->where('odactive', '1')
      ->whereRaw("odcreatedat::date between '" . $inputs['startdate'] . "' and '" . $inputs['enddate'] . "'")
      ->groupBy('odmenuid')
      ->select(
        DB::raw(" sum(odqty) as totalorder"),
        'odmenuid');
      
    $data = Menu::joinSub($detailOrder, 'od', function ($join) {
        $join->on('menus.id', '=', 'od.odmenuid');})
      ->select(
        'menuname',
        'menuprice',
        'od.totalorder',
        DB::raw('od.totalorder*menuprice as grantotal'))
      ->orderBy('od.totalorder', 'DESC')->get();

    return $data;
  }

}