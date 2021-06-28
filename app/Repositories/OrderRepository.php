<?php
namespace App\Repositories;

use App\Models\Order;
use App\Models\OrderDetail;
use Illuminate\Support\Facades\Log;
use DB;
use Carbon\Carbon;
use Exception;

class OrderRepository
{
  public static function grid($filter, $perms)
  {
    $q = Order::where('orderactive', '1')
      ->select(
        'orders.id',
        'orderinvoice',
        'ordercustname',
        DB::raw("to_char(orderdate, 'dd-mm-yyyy HH24:MI:SS') as orderdate"),
        'orderprice', 
        DB::raw("CASE WHEN orders.orderstatus = 'DRAFT' THEN '<span class=". '"' ."badge badge-warning". '"' .">Draf</span>' 
          WHEN orders.orderstatus = 'DP' THEN '<span class=". '"' ."badge badge-secondary". '"' .">Bayar Dimuka</span>'
          WHEN orders.orderstatus = 'PAID' THEN '<span class=". '"' ."badge badge-info". '"' .">Lunas</span>'
          WHEN orders.orderstatus = 'COMPLETED' THEN '<span class=". '"' ."badge badge-primary". '"' .">Selesai</span>'
          WHEN orders.orderstatus = 'VOIDED' THEN '<span class=". '"' ."badge badge-danger". '"' .">Batal</span>' END as orderstatuscase"),
        //Action
        DB::raw("CASE WHEN orderpaid = '0' and 1 = " . $perms['save'] . " THEN true ELSE false END as can_save"),
        DB::raw("CASE WHEN orders.orderstatus = 'DRAFT' AND 1 = " . $perms['delete'] . " THEN true ELSE false END as can_delete")
      );

      $count = $q->count();

      if(!empty($filter->filterDate)){
        $q->whereRaw("ordercreatedat::date between '". $filter->filterDate->from . "'::date and '" . $filter->filterDate->to . "'::date");
      }
      
      //Filter Kolom.
      if (!empty($filter->filterText) && !empty($filter->filterColumn)){
        // if (empty($filterText)) continue;
        $trimmedText = trim($filter->filterText);
        $filterCol = $filter->filterColumn;

        if($filterCol == "orderprice"){
          $filterCol = "cast(orderprice as varchar)";
        }

        $text = strtolower(implode('%', explode(' ', $trimmedText)));
        $q->whereRaw('upper('.$filterCol .') like upper(?)', [ '%' . $text . '%']);
      }

      //Filter Status.
      if (!empty($filter->filterStatus)){
        // if (empty($filterText)) continue;
        $trimmedFilter= trim($filter->filterStatus);
        // $text = strtolower(implode('%', explode(' ', $trimmedText)));
        $q->where('orderstatus', $trimmedFilter);
      }
  
      $countFiltered = $q->count();
      // Order.
      if (!empty($filter->sortColumns)){
        foreach ($filter->sortColumns as $value){
          $field = $value->field;
          if (empty($field)) continue;
          $q->orderBy($field, $value->dir);
        }
      } else {
        $q->orderByRaw("case orderstatus when 'DRAFT' then 1 else 2 end, ordercreatedat desc");
      }
  
      // Paging.
      $q->skip($filter->pageOffset)
        ->take($filter->pageLimit);
  
      $grid = new \stdClass();
      $grid->recordsTotal = $count;
      $grid->recordsFiltered = $countFiltered;
      $grid->data = $q->get();
  
      return $grid;
  }
  public static function orderChart($filter, $range, $month)
  {
    $transaction = Order::select(DB::raw('ordercreatedat::date as date,sum(orderprice) as total'))
      ->where('orderstatus', 'PAID')
      ->where('orderactive', '1')
      ->whereRaw("orderdate::date between '". $filter['awal'] . "'::date and '" . $filter['akhir'] . "'::date")
      ->groupBy(DB::raw('ordercreatedat::date'))->get();
    
    $expenses = DB::table('expenses')
      ->where('expenseactive', '1')
      ->whereNotNull('expenseexecutedat')
      ->whereRaw("expensedate::date between '". $filter['awal'] . "'::date and '" . $filter['akhir'] . "'::date")
      ->groupBy(DB::raw('expensedate::date'))
      ->select(
        DB::raw('expensedate::date as date,sum(expenseprice) as total')
      )->get();
      
    $data = new \StdClass();
    $inc = [];
    $exp = [];
    foreach ($range as $row) {
      $f_date = strlen($row) == 1 ? 0 . $row:$row;
      $date = $month . "-".  $f_date;
      $totalInc = $transaction->firstWhere('date', $date);
      $totalExp = $expenses->firstWhere('date', $date);
      
      array_push($inc,$totalInc ? $totalInc->total:0);
      array_push($exp,$totalExp ? $totalExp->total:0);
    }
    
    $data->chartIncome = implode(",", $inc);
    $data->chartExpense = implode(",", $exp);
    $data->chartTgl = implode(",", $range);
    return $data;
  }

  public static function preOrderGrid($perms)
  {
    return Order::where('orderactive', '1')
    ->join('orderdetail as od', 'odorderid', 'orders.id')
    ->whereIn('orderstatus', ['DP', 'PAID'])
    ->where('od.odtype', 'PO')
    ->select('orders.id',
      'orderinvoice', 
      'ordercustname', 
      DB::raw("to_char(orderdate, 'dd-mm-yyyy HH24:MI:SS') as orderdate"), 
      DB::raw("to_char(orderestdate, 'dd-mm-yyyy') as orderestdate"),
      'orderprice',
      DB::raw("CASE WHEN orders.orderstatus = 'DRAFT' THEN '<span class=". '"' ."badge badge-warning". '"' .">Draf</span>' 
      WHEN orders.orderstatus = 'DP' THEN '<span class=". '"' ."badge badge-secondary". '"' .">Bayar Dimuka</span>'
      WHEN orders.orderstatus = 'PAID' THEN '<span class=". '"' ."badge badge-info". '"' .">Lunas</span>'
      WHEN orders.orderstatus = 'COMPLETED' THEN '<span class=". '"' ."badge badge-primary". '"' .">Selesai</span>'
      WHEN orders.orderstatus = 'VOIDED' THEN '<span class=". '"' ."badge badge-danger". '"' .">Batal</span>' END as orderstatuscase"),
      DB::raw($perms['save']))
    ->orderBy('orderestdate', 'asc')
    ->get();
  }


  public static function getOrder($respon, $id)
  {
    $data = new \StdClass();
    if($id){
      $data = Order::leftJoin('users as uvoid', 'uvoid.id', 'ordervoidedby')
        ->leftJoin('users as ucom', 'ucom.id', 'ordercompletedby')
        ->where('orderactive', '1')
        ->where('orders.id', $id)
        ->select(
          'orders.id',
          'orderinvoice',
          DB::raw("to_char(orderdate, 'dd/mm/yyyy HH24:MI') as orderdate"),
          DB::raw("to_char(orderpaidat, 'dd/mm/yyyy HH24:MI') as orderpaiddate"),
          'orderprice',
          'orderdp',
          DB::raw("to_char(orderestdate, 'dd/mm/yyyy') as orderestdate"),
          'ordercustname',
          'orderpaid',
          'orderpaidprice',
          'orderstatus',
          'orderdetail',
          'orderpaymentmethod',
          'ordervoidedat',
          'ordervoidreason',
          'ordervoidedby',
          'orderdiscountprice',
          'ordercompleteddate',
          'ordercompletedby',
          'orderremainingpaid',
          'ucom.username as ordercompletedname',
          'uvoid.username as ordervoidedusername',
          DB::raw("CASE WHEN orders.orderstatus = 'DRAFT' THEN 'Diproses' WHEN orders.orderstatus = 'DP' THEN 'Bayar Dimuka' WHEN orders.orderstatus = 'PAID' THEN 'Lunas' WHEN orders.orderstatus = 'VOIDED' THEN 'Batal' WHEN orders.orderstatus = 'COMPLETED' THEN 'Selesai' END as orderstatuscase")
        )->first();
      if($data == null){
        $respon['status'] = 'error';
        array_push($respon['messages'],'Pesanan tidak ditemukan!');
      } else {
        $data->subs = self::getSubOrder($id);
        
        $data->odTypeCek= OrderDetail::where('odorderid', $id)->select(DB::raw("CASE WHEN odtype = 'PO' THEN true ELSE false END as odcek"))->orderBy('odtype', 'ASC')->first();

        $respon['status'] = 'success';
        $respon['data'] = $data;
      }
    } else {
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
        ->leftJoin("showcases as s", "odshowcaseid", "s.id")
      ->leftJoinSub($promo, 'promo', function ($join) {
        $join->on('products.id', '=', 'promo.spproductid');
        $join->on('odpromoid', '=', 'promoid');
      })
      ->where('odactive', '1')
      ->where('odorderid', $idOrder)
      ->select(
        'orderdetail.id',
        'odproductid',
        DB::raw("productname as odmenutext"),
        'odqty',
        'odtype',
        'odprice',
        'odtotalprice',
        'odremark',
        'odindex',
        'odispromo',
        'odpromoid',
        'odpriceraw',
        'odtotalpriceraw',
        'promodiscount',
        'showcasecode'
        )
      ->get();
  }

  public static function save($respon, $id, $inputs, $loginid)
  {
    $respon['success'] = false;
    $id = $id != null ? $id : $inputs['id'] ;
    $details = $inputs['dtl'];
    try{
      DB::transaction(function () use (&$respon, $id, $inputs, $loginid)
      {
        $valid = self::saveOrder($respon, $id, $inputs, $loginid);
        if (!$valid['success']) return $respon;

        if($id != null){
          $valid = self::removeMissingDetails($respon, $id, $inputs['dtl'], $loginid);
        }

        $valid = self::saveDetailOrder($respon, $id, $inputs['dtl'], $loginid);
        if (!$valid['success']) return $respon;

        $respon['status'] = 'success';
      });
    } catch (\Exception $e) {
      // $eMsg = $e->getMessage() ?? "NOT_RECORDED";
      // Log::channel('errorKape')->error(trim($eMsg));
      $respon['status'] = 'error';
    }
    return $respon;
  }

  public static function saveOrder(&$respon, $id, $inputs, $loginid)
  {
    try{
      $data = "";
      if($id == null){
        $inv = self::generateInvoice();
        $data = Order::create([
          'orderinvoice' => $inv['invoice'],
          'orderinvoiceindex' => $inv['index'],
          'orderdate' => now()->toDateTimeString(),
          'orderprice' => $inputs['orderprice'] ?? 1,
          'orderstatus' => 'DRAFT',
          'orderdetail' => $inputs['orderdetail'] ?? null,
          'orderpaid' => '0',
          'orderactive' => '1',
          'ordercreatedat' => now()->toDateTimeString(),
          'ordercreatedby' => $loginid,
        ]);
        if($data->id != null){
          $respon['id'] = $data->id;
          $respon['success'] = true;
          array_push($respon['messages'], 'OK');
        } else {
          throw new Exception('rollback');
        }
      } else {
        $data = Order::where('orderactive', '1')
          ->where('id', $id)
          ->update([
            'orderprice' => $inputs['orderprice'] ?? 1,
            // 'orderstatus' => 'ADDITIONAL',
            'orderdetail' => $inputs['orderdetail'] ?? "",
            'ordermodifiedat' => now()->toDateTimeString(),
            'ordermodifiedby' => $loginid,
          ]);
        $respon['id'] = $id;
        $respon['success'] = true;
        array_push($respon['messages'], 'Pesanan berhasil diubah.');
      }
    } catch (\Exception $e) {
      $eMsg = $e->getMessage() ?? "NOT_RECORDED";
      Log::channel('errorKape')->error("OrderHeader_" . trim($eMsg));
      throw new Exception('rollbacked');
    }
    return $respon;
  }

  public static function removeMissingDetails(&$respon, $id, $details, $loginid)
  {
    $ids = Array();
    foreach($details as $dt){
      array_push($ids,$dt->id != null ? $dt->id :0);
    }
    try{
      $data = OrderDetail::where('odactive', '1')
        ->where('odorderid', $id)
        ->whereNotIn('id', $ids)
        ->update([
          'odactive' => '0',
          'odmodifiedby' => $loginid,
          'odmodifiedat' => now()->toDateTimeString()
          ]);
      $respon['success'] = true;
    } catch(Exception $e){
      $eMsg = $e->getMessage() ?? "NOT_RECORDED";
      Log::channel('errorKape')->error("DeleteSubOrder_" . trim($eMsg));
      throw new Exception('rollbacked');
    }
    return $respon;
  }
  
  public static function saveDetailOrder($respon, $id, $details, $loginid)
  {
    $idHeader = $id != null ? $id : $respon['id'];
    $detRow = "";
    try{
      foreach ($details as $dtl){
        if (!isset($dtl->id)){
          if($dtl->odqty > 0){
            $detRow = OrderDetail::create([
              'odorderid' => $idHeader,
              'odshowcaseid' => $dtl->odshowcaseid,
              'odtype' => $dtl->odtype,
              'odproductid' => $dtl->odproductid,
              'odqty' => $dtl->odqty,
              'odprice' => $dtl->odprice,
              'odtotalprice' => ($dtl->odprice * $dtl->odqty),
              'odpriceraw' => $dtl->odpriceraw,
              'odtotalpriceraw' => ($dtl->odpriceraw * $dtl->odqty),
              'odremark' => $dtl->odremark,
              'odindex' => $dtl->index,
              'odispromo' => isset($dtl->odpromoid) ? '1' : '0',
              'odpromoid' => $dtl->odpromoid ?? null,
              'odactive' => '1',
              'odcreatedat' => now()->toDateTimeString(),
              'odcreatedby' => $loginid
            ]);
          }
        } else {
          $detRow = OrderDetail::where('odactive', '1')
            ->where('id', $dtl->id);
          if($dtl->odqty > 0){
            $detRow->update([
              'odproductid' => $dtl->odproductid,
              'odshowcaseid' => $dtl->odshowcaseid,
              'odtype' => $dtl->odtype,
              'odqty' => $dtl->odqty,
              'odprice' => $dtl->odprice,
              'odtotalprice' => ($dtl->odprice * $dtl->odqty),
              'odpriceraw' => $dtl->odpriceraw,
              'odtotalpriceraw' => ($dtl->odpriceraw * $dtl->odqty),
              'odremark' => $dtl->odremark,
              'odindex' => $dtl->index,
              'odispromo' => isset($dtl->odpromoid) ? '1' : '0',
              'odpromoid' => $dtl->odpromoid ?? null,
              'odmodifiedat' => now()->toDateTimeString(),
              'odmodifiedby' => $loginid
            ]);
          }else{
            $detRow->update([
              'odproductid' => $dtl->odproductid,
              'odshowcaseid' => $dtl->odshowcaseid,
              'odtype' => $dtl->odtype,
              'odqty' => $dtl->odqty,
              'odprice' => $dtl->odprice,
              'odtotalprice' => ($dtl->odprice * $dtl->odqty),
              'odpriceraw' => $dtl->odpriceraw,
              'odtotalpriceraw' => ($dtl->odpriceraw * $dtl->odqty),
              'odispromo' => isset($dtl->odpromoid) ? '1' : '0',
              'odpromoid' => $dtl->odpromoid ?? null,
              'odremark' => $dtl->odremark,
              'odindex' => $key,
              'odactive' => '0',
              'odmodifiedat' => now()->toDateTimeString(),
              'odmodifiedby' => $loginid
            ]);
          }
        }
      }

      $respon['success'] = true;
    }catch(\Exception $e){
      dd($e);
      $eMsg = $e->getMessage() ?? "NOT_RECORDED";
      Log::channel('errorKape')->error("OrderDetailSave_" . trim($eMsg));
      throw new Exception('rollbacked');
      $respon['success'] = false;
    }
    return $respon;
  }

  public static function dbOrderDetail($db)
  {
    $ui = new \StdClass();
    
    $ui->id = $db->id ?? null;
    $ui->odorderid = $db->odorderid ?? null;
    $ui->odmenuid = $db->odmenuid ?? null;
    $ui->odmenutext = $db->odmenutext ?? null;
    $ui->odmenutype = $db->odmenutype ?? null;
    $ui->odqty = $db->odqty ?? null;
    $ui->odprice = $db->odprice ?? "";
    $ui->odtotalprice = $db->odtotalprice ?? "";
    $ui->odremark = $db->odremark ?? "";
    
    return $ui;
  }
  
  public static function dbOrderHeader($db)
  {
    $ui = new \StdClass();
    
    $ui->id = $db->id ?? null;
    $ui->orderinvoice = $db->orderinvoice ?? null;
    $ui->orderboardid = $db->orderboardid ?? null;
    $ui->orderboardtext = $db->orderboardtext ?? null;
    $ui->ordertype = $db->ordertype ?? "";
    $ui->ordertypetext = $db->ordertypetext ?? null;
    // $ui->ordercustname = $db->ordercustname ?? "";
    $ui->orderdate = $db->orderdate ?? null;
    $ui->orderprice = $db->orderprice ?? null;
    $ui->orderstatus = $db->orderstatus ?? null;
    $ui->orderpaymentmethod = $db->orderpaymentmethod ?? null;
    $ui->orderpaid = $db->orderpaid ?? null;
    $ui->orderpaidprice = $db->orderpaidprice ?? null;
    $ui->orderdiscountprice = $db->orderdiscountprice ?? null;
    $ui->orderpaidremark = $db->orderpaidremark ?? null;
    $ui->ordervoid = $db->ordervoid ?? null;
    $ui->ordervoidedusername = $db->ordervoidedusername ?? null;
    $ui->ordervoidedat = $db->ordervoidedat ?? null;
    $ui->ordervoidreason = $db->ordervoidreason ?? null;
    $ui->ordercreatedat = $db->ordercreatedat ?? null;
    $ui->ordercreatedname = $db->ordercreatedname ?? null;
    $ui->ordermodifiedat = $db->ordermodifiedat ?? null;
    $ui->ordermodifiedname = $db->ordermodifiedname ?? null;

    $ui->subOrder = Array();

    return $ui;
  }

  public static function generateInvoice()
  {
    $prefix = DB::table('settings')->where('settingactive', '1')->where('settingkey', 'KodeInvoice')->select('settingvalue')->first();
    $invoicePref = $prefix->settingvalue ?? "Cf";
    $invoice = Array();
    $q = Order::where('orderactive', '1')
      ->orderBy('ordercreatedat', 'DESC')
      ->select('orderinvoiceindex', DB::raw("extract(day from now()) as tglawal"))
      ->first();
    $cekTgl = $q->tglawal ?? Carbon::now()->format('d');
    if($q == null || $cekTgl == 1){
      $invoice['index'] = 1;
      $invoice['invoice'] = $invoicePref . Carbon::now()->format('ymd')."001";
    } else {
      $invoice['index'] = $q->orderinvoiceindex + 1;
      $cek = strlen($invoice['index']);
      $incr = "";
      if($cek == 1){
        $incr = "00" . ($invoice['index']);
      } else if($cek == 2){
        $incr = "0" . ($invoice['index']);
      } else {
        $incr = $invoice['index'];
      }
      $invoice['invoice'] = $invoicePref . Carbon::now()->format('ymd'). $incr ;
    }
    return $invoice;
  }

  public static function delete($respon, $id, $loginid)
  {
    try{
      DB::beginTransaction();
      $data = Order::where('orderactive', '1')
        ->where('id', $id)
        ->first();
      $datasub = OrderDetail::where('odactive', '1')
        ->where('odorderid', $id);
      
      $ceksub = $data->whereIn('orderstatus', ["DRAFT"])->first();

      if($ceksub == null)
        throw new Exception('subDelivered');

      $upd = $datasub->update([
        'odactive' => '0',
        'odmodifiedby' => $loginid,
        'odmodifiedat' => now()->toDateTimeString()
      ]);
      
      if ($data != null){
        $data->update([
          'orderactive' => '0',
          'orderstatus' => 'DELETED',
          'ordermodifiedby' => $loginid,
          'ordermodifiedat' => now()->toDateTimeString()
        ]);
      }

      DB::commit();
      $respon['status'] = 'success';
      array_push($respon['messages'], 'Pesanan berhasil dihapus');
    }catch(\Exception $e){
      $eMsg = $e->getMessage() ?? "NOT_RECORDED";
      Log::channel('errorKape')->error("OrderDelete_" . trim($eMsg));
      $ext = "";
      DB::rollback();
      $respon['status'] = 'error';
      if ($e->getMessage() === 'subDelivered') 
        $ext = "Tidak dapat hapus Pesanan sudah dibayar.";
      array_push($respon['messages'], 'Kesalahan!' . $ext);
    }
 
    return $respon;
  }

  public static function void($respon, $id, $loginid, $inputs)
  {
    $data = Order::where('orderactive', '1')
      ->where('id', $id)
      ->first();

    $cekDelete = false;
    if ($data != null){
      $data->update([
        'ordervoidreason' => $inputs['ordervoidreason'] ,
        'orderstatus' => 'VOIDED',
        'ordervoid' => '1',
        'ordermodifiedby' => $loginid,
        'ordermodifiedat' => now()->toDateTimeString(),
        'ordervoidedby' => $loginid,
        'ordervoidedat' => now()->toDateTimeString()
      ]);       
      $cekDelete = true;
      $respon['status'] = 'success';
      array_push($respon['messages'], 'Pesanan Dibatalkan');
    }else{
      $respon['status'] = 'error';
      array_push($respon['messages'], 'Kesalahan');
    }
    
    return $respon;
  }

  public static function paid($respon, $id, $loginid, $inputs)
  {
    $data = Order::where('orderactive', '1')
      ->where('id', $id)
      ->first();

    $cekDelete = false;
    if ($data != null){
      $data->update([
        'orderpaymentmethod' => $inputs['orderpaymentmethod'],
        'orderpaidprice' => $inputs['orderpaidprice'],
        'ordercustname' => $inputs['ordercustname'],
        'orderdiscountprice' => $inputs['orderdiscountprice'],
        'orderstatus' => $inputs['orderstatus'],
        'orderestdate' => $inputs['orderestdate']??null,
        'orderdp' => $inputs['orderdp']??null,
        'orderremainingpaid' => $inputs['orderremainingpaid']??null,
        'ordermodifiedby' => $loginid,
        'ordermodifiedat' => now()->toDateTimeString()
      ]);
      if ($inputs['orderpaidprice']){
        $data->update([
          'orderstatus' => 'PAID',
          'orderpaid' => '1',
          'orderpaidby' => $loginid,
          'orderpaidat' => now()->toDateTimeString(),
        ]);
        $respon['status'] = 'success';
        array_push($respon['messages'], 'Pesanan Dibayar Lunas');
      }else{
        $respon['status'] = 'success';
        array_push($respon['messages'], 'Pesanan Dibayar Dimuka');
      }
      if ($inputs['orderstatus'] == "COMPLETED"){
        $data->update([
          'orderstatus' => 'COMPLETED',
          'ordercompletedby' => $loginid,
          'ordercompleteddate' => now()->toDateTimeString()
        ]);
        $respon['status'] = 'success';
        array_push($respon['messages'], 'Pesanan Diambil');
      }       
      $cekDelete = true;

    }else{
      $respon['status'] = 'error';
      array_push($respon['messages'], 'Kesalahan');
    }
    return $respon;
  }

  public static function complete($respon, $id, $loginid)
  {
    $data = Order::where('orderactive', '1')
      ->where('id', $id)
      ->first();

    $cekDelete = false;
    if ($data != null){
      $data->update([
        'orderstatus' => 'COMPLETED',
        'ordercompletedby' => $loginid,
        'ordercompleteddate' => now()->toDateTimeString()
      ]);
        $respon['status'] = 'success';
        array_push($respon['messages'], 'Pesanan Diambil');
      
      $cekDelete = true;

    }else{
      $respon['status'] = 'error';
      array_push($respon['messages'], 'Kesalahan');
    }
    return $respon;
  }

  public static function getOrderReceipt($id)
  {
    $dataOrder = new \StdClass();
    $order = Order::join('boards', 'boards.id', 'orderboardid')
      ->where('orderactive', '1')
      ->where('orders.id', $id)
      ->select(
        'orderinvoice',
        'orderprice',
        'orderdate',
        DB::raw("case when ordertype = 'DINEIN' then 'Makan Ditempat' else 'Bungkus' end as ordertype"),
        DB::raw("boardnumber || ' - Lantai ' || boardfloor as boardnumber")
      )->first();
      
    if($order != null){
      $dataOrder->invoice = $order->orderinvoice;
      $dataOrder->price = $order->orderprice;
      $dataOrder->date = Carbon::parse($order->orderdate)->format('d/m/Y H:i') ?? null;
      $dataOrder->orderType = $order->ordertype;
      $dataOrder->noTable = $order->boardnumber;
      $dataOrder->detail = Array();

      $subs = self::getSubOrder($id);
      foreach($subs as $sub){
        $temp = new \StdClass();
        $temp->text = $sub->odmenutext;
        $temp->qty = $sub->odqty;
        $temp->price = $sub->odprice;
        $temp->promo = $sub->odispromo;
        $temp->totalPrice = $sub->odtotalprice;
        $temp->priceraw = $sub->odpriceraw;
        $temp->totalPriceraw = $sub->odtotalpriceraw;
        $temp->promodiscount = $sub->promodiscount;
  
        array_push($dataOrder->detail, $temp);
      }
    }
    return $dataOrder;
  }

  public static function getOrderReceiptkasir($id)
  {
    $dataOrder = new \StdClass();
    $data = Order::where('orderactive', '1')->where('orders.id', $id)->first();
        $order = $data->where('orders.id', $id)
        ->select(
          'orderinvoice',
          'ordercustname',
          'orderprice',
          'orderdate',   
          'orderpaidprice', 
          'orderpaymentmethod',
          'orderdiscountprice',
          'orderdp',
          'orderestdate',
          'ordercompleteddate',
          'orderremainingpaid',
          DB::raw("CASE WHEN orders.orderstatus = 'DP' THEN 'Bayar Dimuka' WHEN orders.orderstatus = 'COMPLETED' THEN 'komplit' WHEN orders.orderstatus = 'PAID' THEN 'Lunas' END as orderstatuscase"), 
        )->first();
      
      // dd($order);
    if($order != null){
      $dataOrder->invoice = $order->orderinvoice;
      $dataOrder->price = $order->orderprice;
      $dataOrder->customer = $order->ordercustname;
      $dataOrder->dp = $order->orderdp;
      $dataOrder->paidprice = $order->orderpaidprice;
      $dataOrder->discountprice = $order->orderdiscountprice;
      $dataOrder->payment = $order->orderpaymentmethod;
      $dataOrder->status = $order->orderstatuscase;
      $dataOrder->repaid = $order->orderremainingpaid;
      $dataOrder->date = Carbon::parse($order->orderdate)->format('d/m/Y H:i') ?? null;
      $dataOrder->estdate = $order->orderestdate ? Carbon::parse($order->orderestdate)->format('d/m/Y') : null;
      $dataOrder->comdate = $order->ordercompleteddate ? Carbon::parse($order->ordercompleteddate)->format('d/m/Y H:i') : null;

      $dataOrder->detail = Array();

      $subs = self::getSubOrder($id);
      foreach($subs as $sub){
        $temp = new \StdClass();
        $temp->text = $sub->odmenutext;
        $temp->qty = $sub->odqty;
        $temp->price = $sub->odprice;
        $temp->totalPrice = $sub->odtotalprice;
        $temp->priceraw = $sub->odpriceraw;
        $temp->totalPriceraw = $sub->odtotalpriceraw;
        $temp->promodiscount = $sub->promodiscount;
        $temp->promo = $sub->odispromo;
        $temp->code = $sub->showcasecode;
  
        array_push($dataOrder->detail, $temp);
      }
    }
    return $dataOrder;
  }

  private static function getNotif()
  {
    return DB::table('orderdetail as od')
      ->join('orders as o', 'o.id', 'od.odorderid')
      ->join('products as p', 'p.id', 'odproductid')
      ->where('orderactive', '1')
      ->where('odactive', '1')
      ->whereNull('ordervoid')
      ->where('odtype', 'PO')
      ->where('orderpaid', '0')
      ->whereNotIn('orderstatus', Array('DRAFT', 'COMPLETED', 'VOID'));
  }

  public static function notifCount($respon)
  {
    $count = self::getNotif()
      ->select(DB::raw('true'))
      ->first();
    
    $respon['status'] = $count != null ? 'success' : 'error';

    return $respon;
  }

  public static function notifTopbar($respon)
  {
    $data = self::dashboardPO(2);
    $respon['status'] = count($data) > 0 ? 'success' : 'error';
    $respon['data'] = count($data) > 0 ? $data  : [];

    return $respon;
  }

  public static function dashboardCount()
  {
    $qOrder = Order::where('orderactive', '1')
      ->whereNotIn('orderstatus', Array('DRAFT', 'VOIDED'))
      ->count();

    $qDraft = Order::where('orderactive', '1')
      ->where('orderstatus', 'DRAFT')
      ->count();
    
    $qExpense = DB::table('expenses as e')
      ->where('expenseactive', '1')
      ->whereNotNull('expenseexecutedat')
      ->count();
    
    $qPO = DB::table('orderdetail as od')
      ->join('orders as o', 'o.id', 'od.odorderid')
      ->whereNotIn('orderstatus', Array('DRAFT', 'VOIDED'))
      ->where('orderactive', '1')
      ->where('odactive', '1')
      ->where('orderpaid', '0')
      ->where('odtype', 'PO')
      ->sum('odqty');
    
    $qRstock = DB::table('product_stock')
      ->sum('qty');

    return Array(
      'orderCount' => $qOrder,
      'orderDraft' => $qDraft,
      'expenseCount' => $qExpense,
      'preOrderSum' => $qPO,
      'stockSum' => $qRstock
    );
  }

  public static function dashboardPO($limit = 5)
  {
    return self::getNotif()->select(
        'o.id',
        'ordercustname',
        'productname',
        DB::raw("to_char(orderestdate, 'dd-mm-yyyy') as estdate"),
        'odqty',
        'odremark'
      )
      ->orderBy('orderestdate', 'ASC') // ORDER BY ESTDATE ASC
      ->limit($limit)->get();
  }
}