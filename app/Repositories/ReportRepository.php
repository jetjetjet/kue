<?php
namespace App\Repositories;

use App\Models\Product;
use DB;

class ReportRepository
{
  public static function grid($filter)
  { 
    $result = array();
    if (!empty($filter['startdate']) && !empty($filter['enddate'])){
      $params = array(
        $filter['startdate'] ?? null,
        $filter['enddate'] ?? null,
        $filter['status'] ?? null,
        intval($filter['expense']) ?? null);
      $paramsQuery = implode(',', array_map(function ($val){ return '?'; }, $params));
      $rows = DB::select('select * from report_transaction(' . $paramsQuery . ')', $params);

      foreach ($rows as $row){
        $model = new \stdClass();
        $model->id = $row->id;
        $model->trxtype = $row->trxtype;
        $model->trxcode = $row->trxcode;
        $model->customername = $row->customername;
        $model->trxname = $row->trxname;
        $model->trxdate = $row->trxdate;
        $model->debit = $row->debit;
        $model->kredit = $row->kredit;
        $model->trxstatus = $row->trxstatus;
        
        array_push($result, $model);
      }
    }
    return $result;
  }
  
  public static function sumTrx($filter)
  { 
    $result = new \stdClass();
    if (!empty($filter['startdate']) && !empty($filter['enddate'])){
      $params = array(
        $filter['startdate'] ?? null,
        $filter['enddate'] ?? null,
        $filter['status'] ?? null,
        intval($filter['expense']) ?? null);
      $paramsQuery = implode(',', array_map(function ($val){ return '?'; }, $params));
      $result = DB::select('select * from report_sumtransaction(' . $paramsQuery . ')', $params);
    }
    return $result;
  }

  public static function getProductReport($inputs)
  {
    $detailOrder = DB::table('orderdetail')
      ->where('odactive', '1')
      ->whereRaw("odcreatedat::date between '" . $inputs['startdate'] . "' and '" . $inputs['enddate'] . "'")
      ->groupBy('odproductid')
      ->select(
        DB::raw(" sum(odqty) as totalorder"),
        'odproductid');
      
    $data = Product::joinSub($detailOrder, 'od', function ($join) {
        $join->on('products.id', '=', 'od.odproductid');})
      ->select(
        'productname',
        'productprice',
        'od.totalorder',
        DB::raw('od.totalorder*productprice as grantotal'))
      ->orderBy('od.totalorder', 'DESC')->get();

    return $data;
  }

}