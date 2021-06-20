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
  private static function orderBoard($filters)
  {
    $qOrder = Order::where('orderactive', '1')
      ->whereNotNull('orderboardid')
      ->whereNull('ordervoid')
      ->orderBy('ordercreatedat', 'DESC')
      ->select('id', 'orderstatus', 'orderboardid', 'orderinvoice');
    
    $order = DB::table(DB::raw("({$qOrder->toSql()}) a"))
      ->select(
        DB::raw("distinct on(a.orderboardid) a.id"),
        'a.orderstatus', 'a.orderboardid', 'a.orderinvoice'
      )->mergeBindings($qOrder->getQuery());
    
    $board = DB::table('boards')
      ->leftJoinSub($order, 'o', function ($join) {
        $join->on('boards.id', '=', 'o.orderboardid');
      })
      ->where('boardactive', '1')
      ->select(
        'o.orderstatus',
        DB::raw("case when o.orderstatus = 'PAID' or o.orderstatus = 'VOIDED' then true
        when o.orderstatus is null then true else false end as boardstatus"),
        'o.id as orderid',
        'boards.id as boardid',
        'boardfloor',
        'o.orderinvoice',
        'boardnumber');
        dd($board->toSql());
    if($filters){
      $board = $board->addSelect(
        DB::raw($filters['is_kasir']),
        DB::raw($filters['is_pelayan'])
      );
    }
    return $board;
  }
  public static function orderGrid($filters)
  {
    $f = $filters['is_kasir']  ?? "";
    $fp = $filters['is_pelayan'] ?? "";
    $f1 = DB::raw($f != null?$f. "," : "");
    $f2 = DB::raw($fp != null ?$fp. ",": "");
    $q = DB::select(DB::raw("
      select o.orderstatus,". $f1 . $f2 ."
        case when o.orderstatus = 'PAID' or o.orderstatus = 'VOIDED' then true
              when o.orderstatus is null then true else false end as boardstatus, 
        o.id as orderid, 
        boards.id as boardid, 
        boardfloor, 
        o.orderinvoice, 
        boardnumber from boards 
      left join (
        select distinct on(a.orderboardid) a.id, 
        a.orderstatus, 
        a.orderboardid, 
        a.orderinvoice from (
          select id, 
            orderstatus, 
            orderboardid, 
            orderinvoice from orders 
          where orderactive = '1' 
          and orderboardid is not null 
          and orderpaid = '0'
          and ordervoid is null order by ordercreatedat desc) 
        a) as o 
      on boards.id = o.orderboardid 
      where boardactive = '1'
      order by boardfloor asc, boardnumber asc
    "));
    return $q;
  }

  public static function orderBungkus()
  {
    $dataOrder = Order::where('orderactive','1')
      ->where('ordertype', 'TAKEAWAY')
      ->whereRaw("(orderpaid is null or orderpaid = '0')")
      ->orderBy('ordercreatedat', 'ASC')
      ->select(
        'id',
        'orderinvoice',
        'orderdate',
        'orderprice')
      ->get();
    return $dataOrder;
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

  public static function gridTakeAway($filter)
  {
    $q = Order::where('orderactive', '1')
      ->where('ordertype', 'TAKEAWAY')
      ->select(
        'id',
        'orderinvoice',  
        // 'ordercustname', 
        DB::raw("CASE WHEN orders.ordertype = 'DINEIN' THEN 'Makan ditempat' ELSE 'Bungkus' END as ordertypetext"), 
        'orderdate',
        'orderprice', 
        DB::raw("CASE WHEN orders.orderstatus = 'PROCEED' THEN 'Diproses' WHEN orders.orderstatus = 'COMPLETED' THEN 'Selesai' WHEN orders.orderstatus = 'PAID' THEN 'Lunas' WHEN orders.orderstatus = 'VOIDED' THEN 'Batal' WHEN orders.orderstatus = 'ADDITIONAL' THEN 'Proses Tambah' END as orderstatuscase")
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

    $countFiltered = $q->count();
    // Order.
    if (!empty($filter->sortColumns)){
      foreach ($filter->sortColumns as $value){
        $field = $value->field;
        if (empty($field)) continue;
        $q->orderBy($field, $value->dir);
      }
    } else {
      $q->orderBy('ordercreatedat', 'DESC');
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
 
  public static function gridDineIn($filter)
  {
    $q = Order::where('orderactive', '1')
      ->where('ordertype', 'DINEIN')
      ->join('boards', 'orderboardid' ,'=', 'boards.id')
      ->select(
        'orders.id',
        'orderinvoice', 
        DB::raw("concat('Meja No. ', boardnumber , ' - Lantai ', boardfloor) as orderboardtext"),
        // 'ordercustname', 
        DB::raw("CASE WHEN orders.ordertype = 'DINEIN' THEN 'Makan ditempat' ELSE 'Bungkus' END as ordertypetext"), 
        'orderdate',
        'orderprice', 
        DB::raw("CASE WHEN orders.orderstatus = 'PROCEED' THEN 'Diproses' WHEN orders.orderstatus = 'COMPLETED' THEN 'Selesai' WHEN orders.orderstatus = 'PAID' THEN 'Lunas' WHEN orders.orderstatus = 'VOIDED' THEN 'Batal' WHEN orders.orderstatus = 'ADDITIONAL' THEN 'Proses Tambah' END as orderstatuscase")
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
        if($filterCol == "orderboard"){
          $filterCol = "concat('Meja No. ', boardnumber , ' - Lantai ', boardfloor)";
        } else if($filterCol == "orderprice"){
          $filterCol = "cast(orderprice as varchar)";
        }

        $text = strtolower(implode('%', explode(' ', $trimmedText)));
        $q->whereRaw('upper('.$filterCol .') like upper(?)', [ '%' . $text . '%']);
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
        $q->orderBy('ordercreatedat', 'DESC');
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

  public static function getOrder($respon, $id)
  {
    $data = new \StdClass();
    if($id){
      $data = Order::leftJoin('boards', function($q){
        $q->whereRaw('orderboardid = boards.id')
          ->whereRaw("boardactive = '1'");})
        ->leftJoin('users as uvoid', 'uvoid.id', 'ordervoidedby')
        ->where('orderactive', '1')
        ->where('orders.id', $id)
        ->select(
          'orders.id',
          'orderinvoice',
          DB::raw("concat('Meja No. ', boardnumber , ' - Lantai ', boardfloor) as orderboardtext"),
          DB::raw("case when ordertype = 'DINEIN' then 'Makan Ditempat' else 'Bungkus' end as ordertypetext"),
          'orderboardid',
          'ordertype',
          DB::raw("to_char(orderdate, 'dd/mm/yyyy HH24:MI') as orderdate"),
          DB::raw("to_char(orderpaidat, 'dd/mm/yyyy HH24:MI') as orderpaiddate"),
          'orderprice',
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
          DB::raw("CASE WHEN orders.orderstatus = 'PROCEED' THEN 'Diproses' WHEN orders.orderstatus = 'COMPLETED' THEN 'Selesai' WHEN orders.orderstatus = 'PAID' THEN 'Lunas' WHEN orders.orderstatus = 'VOIDED' THEN 'Batal' WHEN orders.orderstatus = 'ADDITIONAL' THEN 'Proses Tambah' END as orderstatuscase")
        )->first();
      if($data == null){
        $respon['status'] = 'error';
        array_push($respon['messages'],'Pesanan tidak ditemukan!');
      } else {
        $data->subOrder = self::getSubOrder($id);
        $cekDelivered = OrderDetail::where('oddelivered', '0')->where('odorderid', $id)->select(DB::raw("CASE WHEN oddelivered = false THEN '1' else '0' END as odstat"))->first();
        $dId = $cekDelivered->odstat??null;
        $data->getstat = $dId;
      
        $respon['status'] = 'success';
        $respon['data'] = $data;
      }
    } else {
      $respon['data'] = self::dbOrderHeader($data);
    }
    return $respon;
  }

  public static function getDataDapur()
  {
    $temp = Array();
    $data = Order::where('orderactive', '1')
      ->leftJoin('boards', 'boards.id', 'orderboardid')
      ->where('orderstatus', 'PROCEED')
      ->orWhere('orderstatus', 'ADDITIONAL')
      ->orderBy('ordercreatedat')
      ->select(
        'orders.id',
        'orderinvoice',
        DB::raw("case when ordertype = 'DINEIN' then concat('Meja No. ', boardnumber , ' - Lantai ', boardfloor) else '' end as orderboardtext"),
        DB::raw("case when ordertype = 'DINEIN' then 'Makan Ditempat' else 'Bungkus' end as ordertype"),
        'orderdate')
      ->get();

      foreach($data as $d){
        $orderHeader = self::dbOrderHeader($d);
        $subs = OrderDetail::join('menus', 'menus.id', 'odmenuid')
          ->where('odactive', '1')
          ->where('odorderid', $d->id)
          ->where('oddelivered', '0')
          ->orderBy('odindex')
          ->select(
            DB::raw('menuname as odmenutext'),
            'menutype as odmenutype',
            'odqty',
            'odremark'
          )->get();

          foreach($subs as $s){
            $dataSub = self::dbOrderDetail($s);
            array_push($orderHeader->subOrder, $dataSub);
          }
        array_push($temp, $orderHeader);
      }
    return $temp;
  }

  public static function getSubOrder($idOrder)
  {
    $promo = DB::table('promo as p')
      ->join('subpromo as sp', 'sppromoid', 'p.id')
      ->where('spactive', '1')
      ->select(
        'p.id as promoid',
        'spmenuid',
        'promodiscount'
      );

    return OrderDetail::join('menus',function($q){
      $q->whereRaw("menuactive = '1'")
        ->whereRaw("menus.id = odmenuid");})
      ->leftJoinSub($promo, 'promo', function ($join) {
        $join->on('menus.id', '=', 'promo.spmenuid');
        $join->on('odpromoid', '=', 'promoid');
      })
      ->where('odactive', '1')
      ->where('odorderid', $idOrder)
      ->select(
        'orderdetail.id',
        'odmenuid',
        DB::raw("menuname as odmenutext"),
        'odqty',
        'odprice',
        'odtotalprice',
        DB::raw("CASE WHEN oddelivered = true then 'Sudah Diantar' ELSE 'Sedang Diproses' END as oddelivertext"),
        'oddelivered',
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

  private static function cekMejaStatus($boardid)
  {
    $q = DB::table('boards as b')
      ->join('orders as o', 'orderboardid', 'b.id')
      ->where('orderactive', '1')
      ->where('b.id', $boardid)
      // ->whereRaw("(orderpaid is null)")
      ->whereNotIn('orderstatus', ['PAID', 'VOIDED'])
      ->count();
    return $q;
  }

  public static function save($respon, $id, $inputs, $loginid)
  {
    $respon['success'] = false;
    $id = $id != null ? $id : $inputs['id'] ;
    $details = $inputs['dtl'];

    $cekMeja = self::cekMejaStatus($inputs['orderboardid']);
    if($cekMeja > 0 && !$id){
      $respon['status'] = "double";
      array_push($respon['messages'], 'Pesanan sudah dibuat/Meja sudah terisi.');

      return $respon;
    }

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
          'orderboardid' => $inputs['orderboardid'],
          'ordertype' => $inputs['ordertype'],
          // 'ordercustname' => $inputs['ordercustname'],
          'orderdate' => now()->toDateTimeString(),
          'orderprice' => $inputs['orderprice'] ?? 1,
          'orderstatus' => 'PROCEED',
          'orderdetail' => $inputs['orderdetail'] ?? null,
          'orderpaid' => '0',
          'orderactive' => '1',
          'ordercreatedat' => now()->toDateTimeString(),
          'ordercreatedby' => $loginid,
        ]);
        if($data->id != null){
          $respon['id'] = $data->id;
          $respon['success'] = true;
          array_push($respon['messages'], 'Pesanan sudah ditambahkan dan sedang diproses.');
        } else {
          throw new Exception('rollback');
        }
      } else {
        $data = Order::where('orderactive', '1')
          ->where('id', $id)
          ->update([
            'orderboardid' => $inputs['orderboardid'] ?? null,
            'ordertype' => $inputs['ordertype'],
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
    // dd($details);
    $detRow = "";
    try{
      foreach ($details as $dtl){
        if (!isset($dtl->id)){
          if($dtl->odqty > 0){
            $detRow = OrderDetail::create([
              'odorderid' => $idHeader,
              'odmenuid' => $dtl->odmenuid,
              'odqty' => $dtl->odqty,
              'odprice' => $dtl->odprice,
              'odtotalprice' => ($dtl->odprice * $dtl->odqty),
              'odpriceraw' => $dtl->odpriceraw,
              'odtotalpriceraw' => ($dtl->odpriceraw * $dtl->odqty),
              'odremark' => $dtl->odremark,
              'oddelivered' => '0',
              'odindex' => $dtl->index,
              'odispromo' => isset($dtl->odpromoid) ? '1' : '0',
              'odpromoid' => $dtl->odpromoid ?? null,
              'odactive' => '1',
              'odcreatedat' => now()->toDateTimeString(),
              'odcreatedby' => $loginid
            ]);
  
              $updStatus = Order::where('orderactive', '1')
              ->where('id', $idHeader)
              ->update([
                'orderstatus' => 'ADDITIONAL'
              ]);
          }
        } else {
          $detRow = OrderDetail::where('odactive', '1')
            ->where('id', $dtl->id);
          if($dtl->odqty > 0){
            $detRow->update([
              'odmenuid' => $dtl->odmenuid,
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
              'odmenuid' => $dtl->odmenuid,
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

      $doubleCek = OrderDetail::where('odactive', '1')
        ->where('odorderid', $idHeader)
        ->where('oddelivered', '0')
        ->first();
      if($doubleCek == null){
        Order::where('orderactive', '1')
          ->where('id', $idHeader)
          ->where('orderpaid', '0')
          ->update([
            'orderstatus' => 'COMPLETED'
          ]);
      }

      $respon['success'] = true;
    }catch(\Exception $e){
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
    $ui->oddelivered = $db->oddelivered ?? false;
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

  public static function deliver($respon, $id, $idSub, $loginid)
  {
    try{
      DB::beginTransaction();
      $data = OrderDetail::where('id', $idSub)
        ->where('odorderid', $id)
        ->where('oddelivered', '0')
        ->where('odactive', '1');

      $upd = $data->update([
        'oddelivered' => '1',
        'odmodifiedby' => $loginid,
        'odmodifiedat' => now()->toDateTimeString()
      ]);

      $cekDelivered = OrderDetail::where('oddelivered', '0')->where('odactive', '1')->where('odorderid', $id)->first();
      if($cekDelivered == null){
        $updH = Order::where('orderactive', '1')
          ->where('id', $id)->first();
        $headerUpdated = $updH->update(['orderstatus' => 'COMPLETED']);
      }

      DB::commit();
      $respon['status'] = 'success';
      array_push($respon['messages'], 'Menu sudah diantar');
    }catch(\Exception $e){
      $eMsg = $e->getMessage() ?? "NOT_RECORDED";
      Log::channel('errorKape')->error("OrderDeliver_" . trim($eMsg));
      $respon['status'] = 'error';
      array_push($respon['messages'], 'Kesalahan! Tidak dapat memproses.');
    }
    
    return $respon;
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
      
      $ceksub = $datasub->where('oddelivered', '1')->first();
      if($ceksub != null)
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
        $ext = "Tidak dapat hapus Pesanan yang sudah diantar.";
      array_push($respon['messages'], 'Kesalahan!' . $ext);
    }
 
    return $respon;
  }

  public static function deleteMenuOrder($respon, $id, $subId, $loginid)
  {
    $data = OrderDetail::where('odactive', '1')
      ->where('odorderid', $id)
      ->where('id', $subId)
      ->first();

    $cekDelete = false;

    if ($data != null){
      $data->update([
        'odactive' => '0',
        'odmodifiedat' => now()->toDateTimeString(),
        'odmodifiedby' => $loginid
      ]);

      //Update Harga
      $getTotalPrice = OrderDetail::where('odactive', '1') 
        ->where('odorderid', $id)
        ->sum('odtotalprice');
      $updH = Order::where('orderactive', '1')
        ->where('id', $id)
        ->update(['orderprice' => $getTotalPrice]);

      $cekDelivered = OrderDetail::where('oddelivered', '0')->where('odactive', '1')->where('odorderid', $id)->first();
      if($cekDelivered == null){
        $updH = Order::where('orderactive', '1')
          ->where('id', $id)
          ->update(['orderstatus' => 'COMPLETED']);
      }

      $cekDelete = true;
    }

    $respon['status'] = $data != null && $cekDelete ? 'success': 'error';
    $data != null && $cekDelete
      ? array_push($respon['messages'], 'Menu Pesanan Berhasil Dihapus.') 
      : array_push($respon['messages'], 'Menu Pesanan Tidak Ditemukan');
    
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

    $datasub = OrderDetail::where('odactive', '1')->where('odorderid', $id);

    $otype = Order::where('orderactive', '1')->where('id', $id)->where('ordertype', 'TAKEAWAY')->first();

    $cekDelete = false;
    if ($data != null){
      $data->update([
        'orderpaymentmethod' => $inputs['orderpaymentmethod'],
        'orderpaidprice' => $inputs['orderpaidprice'],
        'orderdiscountprice' => $inputs['orderdiscountprice'],
        'orderstatus' => 'PAID',
        'orderpaid' => '1',
        'orderpaidby' => $loginid,
        'orderpaidat' => now()->toDateTimeString(),
        'ordermodifiedby' => $loginid,
        'ordermodifiedat' => now()->toDateTimeString()
      ]);
      if ($otype != null){
        $datasub->update([
          'oddelivered' => '1',
          'odmodifiedat' => now()->toDateTimeString(),
          'odmodifiedby' => $loginid
        ]);
      }       
      $cekDelete = true;
      $respon['status'] = 'success';
      array_push($respon['messages'], 'Pesanan Dibayar');
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
      if($data->orderboardid == null){
        $order = $data->where('orders.id', $id)
        ->select(
          'orderinvoice',
          'orderprice',
          'orderdate',   
          'orderpaidprice', 
          'orderpaymentmethod',
          'orderdiscountprice' 
        )->first();
        $order->boardnumber = null;
        $order->ordertype = 'Bungkus';
      }else{
        $order = $data->join('boards', 'boards.id', 'orderboardid')  
        ->where('orders.id', $id)    
        ->select(
          'orderinvoice',
          'orderprice',
          'orderdate',
          'orderpaidprice', 
          'orderpaymentmethod', 
          DB::raw("case when ordertype = 'DINEIN' then 'Makan Ditempat' else 'Bungkus' end as ordertype"),
          DB::raw("'No.' ||boardnumber || ' - Lantai ' || boardfloor as boardnumber"),
          'orderdiscountprice' 
        )->first();
      }
      // dd($order);
    if($order != null){
      $dataOrder->invoice = $order->orderinvoice;
      $dataOrder->price = $order->orderprice;
      $dataOrder->paidprice = $order->orderpaidprice;
      $dataOrder->discountprice = $order->orderdiscountprice;
      $dataOrder->payment = $order->orderpaymentmethod;
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
        $temp->totalPrice = $sub->odtotalprice;
        $temp->priceraw = $sub->odpriceraw;
        $temp->totalPriceraw = $sub->odtotalpriceraw;
        $temp->promodiscount = $sub->promodiscount;
        $temp->promo = $sub->odispromo;
  
        array_push($dataOrder->detail, $temp);
      }
    }
    return $dataOrder;
  }
}