<?php
namespace App\Repositories;

use App\Models\Product;
use Illuminate\Support\Facades\Log;
use DB;

class ProductRepository
{
  public static function grid($perms)
  {
    return Product::where('productactive', '1')
    ->join('productcategories as pc', 'products.productpcid', '=', 'pc.id')
    ->select('products.id',
      'productcode',
      'productname', 
      'productprice', 
      'pcname as productcategory',
      DB::raw($perms['save']),
      DB::raw($perms['delete']))
    ->get();
  }

  public static function get($respon, $id)
  {
    $data = new \stdClass();
    $respon['data'] = self::getFields($data);

    if($id){
      $respon['data'] = Product::join('productcategories as pc', 'productpcid', 'pc.id')
      ->where('productactive', '1')
      ->where('products.id', $id)
      ->select(
        'productcode',
        'products.id',
        'productpcid',
        'pcname as productpcname',
        'productname', 
        'productdetail',
        'productimg',
        'productprice',
        'productcreatedat',
        'productcreatedby',
        'productmodifiedat',
        'productmodifiedby')
      ->first();

      if($respon['data'] == null){
        $respon['status'] = 'error';
        array_push($respon['messages'],'Data tidak ditemukan!');
      }
    }
    
    return $respon;
  }

  public static function getShowcaseOrder()
  {
    $data = Array();
    $promo = self::getPromo();

    $gets = DB::table('showcases as s')
      ->join('products as prod', 'prod.id','s.showcaseproductid')
      ->join('productcategories as pc', 'pc.id', 'productpcid')
      ->leftJoinSub($promo, 'promo', function ($join) {
        $join->on('prod.id', '=', 'promo.spproductid');
      })
      ->join(DB::raw("(select * from report_getstock()) as gs"), 'gs.productid','s.showcaseproductid')
      ->where('showcaseactive', '1')
      ->whereNull('showcaseexpiredat')
      ->select(
        DB::raw("distinct on(showcaseproductid) showcaseproductid"),
        // DB::raw("string_agg(showcasecode, ',') as showcasecode"),
        'showcasecode',
        'showcaseproductid as productid',
        'productname',
        'productimg', 
        'gs.quantity',
        'productprice as productpriceraw', 
        'productpcid', 
        'pcname',
        'promoname',
        'promodiscount',
        'promostart',
        'promoend',
        'promoid',
        DB::raw("(productprice - COALESCE(promodiscount, 0)) as productprice")
      )->get();
    
    foreach($gets as $cat){
      if(!isset($data[$cat->pcname])){
        $data[$cat->pcname] = Array();
        $data[$cat->pcname]['text'] = $cat->pcname;
        $data[$cat->pcname]['item'] = Array();
      }
      array_push($data[$cat->pcname]['item'], $cat);
    }
    return $data;
  }

  public static function apiDetail($respon, $id)
  {
    $promo = self::getPromo();

    $data = Product::join('productcategories as pc', 'pc.id', 'productpcid')
      ->leftJoinSub($promo, 'promo', function ($join) {
        $join->on('products.id', '=', 'promo.spproductid');
      })
      ->where('pcactive', '1')
      ->where('productactive', '1')
      ->where('products.id', $id)
      ->orderBy('productname', 'ASC')
      ->select(
        'products.id as productid',
        'productname', 
        'productimg', 
        'productprice as productpriceraw', 
        'productpcid', 
        'pcname',
        'promoname',
        'promodiscount',
        'promostart',
        'promoend',
        'promoid',
        DB::raw("(productprice - COALESCE(promodiscount, 0)) as productprice"))
      ->first();
    
    if ($data == null){
      $respon['status'] = 'error';
      array_push($respon['messages'], sprintf('Data %s tidak ditemukan', trans('fields.product')));
    } else {
      $respon['data'] = $data;
      $respon['status'] = 'success';
    }

    return $respon;
  }

  public static function apiShowcaseCode($respon, $id)
  {
    $query = Product::join('showcases as s', 'products.id', 'showcaseproductid')
      ->where('productactive','1')
      ->where('showcaseactive', '1')
      ->where('products.id', $id)
      ->whereNull('showcaseexpiredat')
      ->select('showcasecode')
      ->get();

    if(count($query) <= 0){
      $respon['status'] = 'error';
      array_push($respon['messages'], sprintf('Data %s tidak ditemukan', trans('fields.showcase')));
    } else {
      $data = Array();
      foreach($query as $q){
        array_push($data, $q->showcasecode);
      }
      $respon['data'] = $data;
      $respon['status'] = 'success';
    }

    return $respon;
  }

  public static function getProductOrder()
  {
    $data = Array();
    $promo = self::getPromo();

    $getCat = Product::join('productcategories as pc', 'pc.id', 'productpcid')
      ->leftJoinSub($promo, 'promo', function ($join) {
        $join->on('products.id', '=', 'promo.spproductid');
      })
      ->where('pcactive', '1')
      ->where('productactive', '1')
      ->orderBy('productname', 'ASC')
      ->select(
        'products.id as productid',
        'productname', 
        'productimg', 
        'productprice as productpriceraw', 
        'productpcid', 
        'pcname',
        'promoname',
        'promodiscount',
        'promostart',
        'promoend',
        'promoid',
        DB::raw("(productprice - COALESCE(promodiscount, 0)) as productprice"))
      ->get();

    foreach($getCat as $cat){
      if(!isset($data[$cat->pcname])){
        $data[$cat->pcname] = Array();
        $data[$cat->pcname]['text'] = $cat->pcname;
        $data[$cat->pcname]['item'] = Array();
      }
      array_push($data[$cat->pcname]['item'], $cat);
    }
    
    return $data;
  }

  private static function getPromo()
  {
    return DB::table('promo as p')
      ->join('subpromo as sp', 'sppromoid', 'p.id')
      ->where('promoactive', '1')
      ->where('spactive', '1')
      ->whereRaw("promoend::timestamp without time zone > now()::timestamp without time zone")
      ->whereRaw("promostart::timestamp without time zone < now()::timestamp without time zone")
      ->select(
        'p.id as promoid',
        'spproductid',
        'promoname',
        'promodiscount',
        DB::raw("to_char(promostart, 'dd-mm-yyyy HH24:MI:SS') as promostart"),
        DB::raw("to_char(promoend, 'dd-mm-yyyy HH24:MI:SS') as promoend"),
      );
  }

  public static function save($respon, $inputs, $file, $loginid)
  {
    $oldPath = isset($inputs['productimg']) ? $inputs['productimg'] : null ;
    $filePath = isset($file) ? '/doc/images/' . $file->newName : $oldPath; 
    $id = $inputs['id'] ?? 0;
    $data = Product::where('productactive', '1')
      ->where('id',$id)
      ->first();

    try{
      if ($data != null){
        $data = $data->update([
          'productpcid' => $inputs['productpcid'],
          'productcode' => $inputs['productcode'],
          'productname' => $inputs['productname'],
          'productimg' => $filePath,
          'productdetail' => $inputs['productdetail'],
          'productprice' => $inputs['productprice'],
          'productmodifiedat' => now()->toDateTimeString(),
          'productmodifiedby' => $loginid
        ]);

        $respon['status'] = 'success';
        array_push($respon['messages'], 'Data Product berhasil diubah');
        
      } else {
        $data = Product::create([
          'productpcid' => $inputs['productpcid'],
          'productcode' => $inputs['productcode'],
          'productname' => $inputs['productname'],
          'productimg' => $filePath,
          'productdetail' => $inputs['productdetail'],
          'productprice' => $inputs['productprice'],
          'productactive' => '1',
          'productcreatedat' => now()->toDateTimeString(),
          'productcreatedby' => $loginid
        ]);

        $respon['status'] = 'success';
        array_push($respon['messages'], sprintf('Data %s berhasil ditambah', trans('fields.product')));
      }

    } catch(\Exception $e){
      dd($e);
      $eMsg = $e->getMessage() ?? "NOT_RECORDED";
      Log::channel('errorKape')->error("ProductSave_" .trim($eMsg));
      $respon['status'] = 'error';
      array_push($respon['messages'], 'Error');
    }
    $respon['id'] = ($data->id ?? $inputs['id']) ?? null;
    return $respon;
  }

  public static function delete($respon, $id, $loginid)
  {
    $data = Product::where('productactive', '1')
      ->where('id', $id)
      ->first();

    $cekDelete = false;

    if ($data != null){
      $data->update([
        'productactive' => '0',
        'productmodifiedby' => $loginid,
        'productmodifiedat' => now()->toDateTimeString()
      ]);
      
      $cekDelete = true;
    }

    $respon['status'] = $data != null && $cekDelete ? 'success': 'error';
    $data != null && $cekDelete
      ? array_push($respon['messages'], trans('fields.product').' Berhasil Dihapus.')
      : array_push($respon['messages'], trans('fields.product').' Tidak Ditemukan.');
    
    return $respon;
  }

  public static function topProduct($filters)
  {
    $detailOrder = DB::table('orderdetail')
      ->where('odactive', '1')
      ->groupBy('odproductid')
      ->select(
        DB::raw(" sum(odqty) as totalorder"),
        'odproductid');
      
    if($filters){
      foreach($filters as $f)
      {
        $detailOrder = $detailOrder->whereRaw($f);
      }
    }
    $data = Product::joinSub($detailOrder, 'od', function ($join) {
        $join->on('products.id', '=', 'od.odproductid');})
      ->select(
        'productname',
        'productprice',
        'od.totalorder')
      ->orderBy('od.totalorder', 'DESC')->limit(10)->get();

    return $data;
  }

  public static function productapi($respon)
  {
    $tempdata = Array('Makanan'=>Array(), 'Minuman'=>Array());
    $getCat = Product::join('productcategory as mc', 'mc.id', 'productmcid')
      ->where('mcactive', '1')
      ->where('productactive', '1')
      ->select('productname', 'productimg', 'productprice', 'productavaible', 'producttype')
      ->get();

    foreach($getCat as $data )
    {
      if($data->producttype == 'Makanan'){
      array_push($tempdata['Makanan'], $data);
      }else if($data->producttype == 'Minuman'){
        array_push($tempdata['Minuman'], $data);
      }
    }
    $respon['status'] = 'success';
    $respon['data'] = $tempdata;

    return $respon;
  }

  public static function getProduct()
  {
    $tempdata = Array('Makanan'=>Array(), 'Minuman'=>Array());
    $promo = DB::table('promo as p')
      ->join('subpromo as sp', 'sppromoid', 'p.id')
      ->where('promoactive', '1')
      ->where('spactive', '1')
      ->whereRaw("promoend::timestamp without time zone > now()::timestamp without time zone")
      ->whereRaw("promostart::timestamp without time zone < now()::timestamp without time zone")
      ->select(
        'p.id as promoid',
        'spproductid',
        'promoname',
        'promodiscount',
        DB::raw("to_char(promostart, 'dd-mm-yyyy HH24:MI:SS') as promostart"),
        DB::raw("to_char(promoend, 'dd-mm-yyyy HH24:MI:SS') as promoend"),
      );

    $getCat = Product::join('productcategory as mc', 'mc.id', 'productmcid')
      ->leftJoinSub($promo, 'promo', function ($join) {
        $join->on('products.id', '=', 'promo.spproductid');
      })
      ->where('mcactive', '1')
      ->where('productactive', '1')
      ->orderBy('productname', 'ASC')
      ->select(
        'products.id',
        'productname', 
        'productimg', 
        'productprice as productpriceraw', 
        'productavaible', 
        'producttype', 
        'productmcid', 
        'mcname',
        'promoname',
        'promodiscount',
        'promostart',
        'promoend',
        'promoid',
        DB::raw("(productprice - COALESCE(promodiscount, 0)) as productprice"))
      ->get();
    foreach($getCat as $data )
    {
      if($data->producttype == 'Makanan'){
        if(!isset($tempdata['Makanan'][$data->mcname])){
          $tempdata['Makanan'][$data->mcname] = Array();
          $tempdata['Makanan'][$data->mcname]['nama'] = $data->mcname;
          $tempdata['Makanan'][$data->mcname]['pilihan'] = Array();
        }
        array_push($tempdata['Makanan'][$data->mcname]['pilihan'], $data);
      }else if($data->producttype == 'Minuman'){
        if(!isset($tempdata['Minuman'][$data->mcname])){
          $tempdata['Minuman'][$data->mcname] = Array();
          $tempdata['Minuman'][$data->mcname]['nama'] = $data->mcname;
          $tempdata['Minuman'][$data->mcname]['pilihan'] = Array();
        }
        array_push($tempdata['Minuman'][$data->mcname]['pilihan'], $data);
      }
    }
    
    return $tempdata;
  }

  public static function getFields($model)
  {
    $model->id = null;
    $model->productcode = null;
    $model->productpcid = null;
    $model->productrecipeid = null;
    $model->productname = null;
    $model->productimg= null;
    $model->productdetail= null;
    $model->productprice= null;
    $model->productactive= null;
    $model->productcreatedat= null;
    $model->productcreatedby= null;
    $model->productmodifiedat= null;
    $model->productmodifiedby= null;

    return $model;
  }

  public static function search($cari)
  {
    $promo = self::searchPromo();
    
    return Product::join('productcategory as mc', 'mc.id', 'productmcid')
      ->leftJoinSub($promo, 'promo', function ($join) {
        $join->on('products.id', '=', 'promo.spproductid');
      })
      ->whereRaw('UPPER(productname) LIKE UPPER(\'%'. $cari .'%\')')
      ->where('productactive', '1')
      ->where('productavaible', '1')
      ->whereNull('promoid')
      ->select('products.id', 'mcname as productcategory', 'productname as text', 'producttype', 'productprice')
      ->orderby('productname', 'ASC')
      ->limit(5)
      ->get();
  }

  public static function searchPromo()
  {
    return DB::table('promo as p')
      ->join('subpromo as sp', 'sppromoid', 'p.id')
      ->where('promoactive', '1')
      ->where('spactive', '1')
      ->whereRaw("promoend::timestamp without time zone > now()::timestamp without time zone")
      ->whereRaw("promostart::timestamp without time zone < now()::timestamp without time zone")
      ->select(
        'p.id as promoid',
        'spproductid',
        'promodiscount'
      );

  }

  public static function searchProductShowcase($cari)
  {
    return Product::where('productactive', '1')
      ->whereRaw('UPPER(productname) LIKE UPPER(\'%'. $cari .'%\')')
      ->select('productprice', 
        DB::raw("productcode || ' - ' || productname as text"),
        'productname',
        'productimg',
        'id')
      ->limit(5)
      ->get();
  }
}