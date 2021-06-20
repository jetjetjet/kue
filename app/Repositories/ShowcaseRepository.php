<?php
namespace App\Repositories;

use App\Models\Showcase;
use DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class ShowcaseRepository
{
  public static function grid($perms)
  {
    return Showcase::where('showcaseactive', '1')
    ->join('products as p', 'showcases.showcaseproductid', 'p.id')
    ->select('showcases.id',
      'productname',
      'productcode',
      'productprice',
      'showcaseqty',
      'showcasedate', 
      'showcaseexpdate',
      'showcasestatus',
      DB::raw($perms['save']),
      DB::raw($perms['delete']))
    ->get();
  }

  public static function get($respon, $id)
  {
    $data = new \stdClass();
    $respon['data'] = self::getFields($data);

    if($id){
      $respon['data'] = Showcase::join('products as p', 'showcases.showcaseproductid', 'p.id')
        ->leftJoin('users as cr', 'showcasecreatedby', 'cr.id')
        ->leftJoin('users as mod', 'showcasemodifiedby', 'mod.id')
        ->leftJoin('users as exp', 'showcaseexpiredby', 'exp.id')
        ->leftJoin('users as sold', 'showcasesoldby', 'sold.id')
        ->where('productactive', '1')
        ->where('showcaseactive', '1')
        ->where('showcases.id', $id)
        ->select(
          'showcasecode',
          'showcases.id as id',
          'showcaseproductid',
          'productname',
          'productcode',
          'productprice',
          'productimg',
          'showcaseqty',
          DB::raw("to_char(showcasedate, 'dd-mm-yyyy') as showcasedate"),
          DB::raw("to_char(showcaseexpdate, 'dd-mm-yyyy') as showcaseexpdate"),
          'showcasestatus',
          DB::raw("to_char(showcasecreatedat, 'dd-mm-yyyy hh:mm') as showcasecreatedat"),
          'cr.username as showcasecreatedby',
          DB::raw("to_char(showcasemodifiedat, 'dd-mm-yyyy hh:mm') as showcasemodifiedat"),
          'mod.username as showcasemodifiedby',
          DB::raw("to_char(showcasesoldat, 'dd-mm-yyyy hh:mm') as showcasesoldat"),
          'sold.username as showcasesoldby',
          DB::raw("to_char(showcaseexpiredat, 'dd-mm-yyyy hh:mm') as showcaseexpiredat"),
          'exp.username as showcaseexpiredby')
        ->first();
      if($respon['data'] == null){
        $respon['status'] = 'error';
        array_push($respon['messages'],'Data tidak ditemukan!');
      }
    }
    return $respon;
  }
  
  public static function save($respon, $inputs, $loginid)
  {
    $id = $inputs['id'] ?? 0;
    $data = Showcase::where('showcaseactive', '1')
      ->where('id',$id)
      ->first();
    try{
      if ($data != null){
        $data = $data->update([
          'showcaseproductid' => $inputs['showcaseproductid'],
          'showcaseqty' => $inputs['showcaseqty'],
          'showcasedate' => $inputs['showcasedate'],
          'showcaseexpdate' => $inputs['showcaseexpdate'],
          // 'showcasestatus' => '',
          'showcasemodifiedat' => now()->toDateTimeString(),
          'showcasemodifiedby' => $loginid
        ]);
        
        $respon['status'] = 'success';
        array_push($respon['messages'], 'Data Product berhasil diubah');
        
      } else {
        $data = Showcase::create([
          'showcasecode' => $inputs['randomstr'] .Carbon::now()->format('d'),
          'showcaseproductid' => $inputs['showcaseproductid'],
          'showcaseqty' => $inputs['showcaseqty'],
          'showcasedate' => $inputs['showcasedate'],
          'showcaseexpdate' => $inputs['showcaseexpdate'],
          'showcasestatus' => 'AVAILABLE',
          'showcaseactive' => '1',
          'showcasecreatedat' => now()->toDateTimeString(),
          'showcasecreatedby' => $loginid
        ]);

        $respon['status'] = 'success';
        array_push($respon['messages'], sprintf('Data %s berhasil ditambah', trans('fields.showcase')));
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
    $data = Product::where('showcaseactive', '1')
    ->where('id', $id)
    ->first();

    $cekDelete = false;

    if ($data != null){
      $data->update([
        'showcaseactive' => '0',
        'showcasemodifiedby' => $loginid,
        'showcasemodifiedat' => now()->toDateTimeString()
      ]);
      
      $cekDelete = true;
    }

    $respon['status'] = $data != null && $cekDelete ? 'success': 'error';
    $data != null && $cekDelete
      ? array_push($respon['messages'], sprintf('? Berhasil Dihapus.'), trans('fields.showcase')) 
      : array_push($respon['messages'], sprintf('? Tidak Ditemukan.'), trans('fields.showcase'));
    
    return $respon;
  }

  public static function getFields($model)
  {
    $model->id = null;
    $model->showcasecode = null;
    $model->showcaseproductid = null;
    $model->productname= null;
    $model->productcode= null;
    $model->productprice= null;
    $model->productimg = null;
    $model->showcaseqty = null;
    $model->showcasedate = null;
    $model->showcaseexpdate = null;
    $model->showcasestatus= null;
    $model->showcaseactive= null;
    $model->showcasecreatedat= null;
    $model->showcasecreatedby= null;
    $model->showcasemodifiedat= null;
    $model->showcasemodifiedby= null;
    $model->showcasesoldat= null;
    $model->showcasesoldby= null;
    $model->showcaseexpiredat= null;
    $model->showcaseexpiredby= null;

    return $model;
  }
  
}