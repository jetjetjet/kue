<?php
namespace App\Repositories;

use App\Models\Showcase;
use DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class ShowcaseRepository
{
  public static function grid($filter, $perms)
  {
    $q = Showcase::where('showcaseactive', '1')
    ->join('products as p', 'showcases.showcaseproductid', 'p.id')
    ->leftJoin('product_stock as ps', 'showcases.id', 'ps.stockshowcaseid')
    ->select('showcases.id',
      'showcasecode',
      'productname',
      'productprice',
      'ps.qty as showcaseqty',
      DB::raw("to_char(showcasedate, 'DD-MM-YYYY') as showcasedate"),
      DB::raw("to_char(showcaseexpdate, 'DD-MM-YYYY') as showcaseexpdate"),
      DB::raw("case when showcaseexpiredat is not null then 'Kadaluarsa' when showcaseexpiredat is null and ps.qty > 0  then 'ReadyStock' when showcaseexpiredat is null and ps.qty is null then 'Habis' end as status"),
      DB::raw($perms['save']),
      DB::raw($perms['delete']));
    
    $count = $q->count();

    if(!empty($filter->filterDate)){
      $q->whereRaw("showcasedate::date between '". $filter->filterDate->from . "'::date and '" . $filter->filterDate->to . "'::date");
    }
    
    //Filter Kolom.
    if (!empty($filter->filterText) && !empty($filter->filterColumn)){
      // if (empty($filterText)) continue;
      $trimmedText = trim($filter->filterText);
      $filterCol = $filter->filterColumn;

      $text = strtolower(implode('%', explode(' ', $trimmedText)));
      $q->whereRaw('upper('.$filterCol .') like upper(?)', [ '%' . $text . '%']);
    }

    //Filter Status.
    if (!empty($filter->filterStatus)){
      // if (empty($filterText)) continue;
      $trimmedFilter= trim($filter->filterStatus);
      $fText;
      if($trimmedFilter == 'ReadyStock'){
        $fText = 'showcaseexpiredat is null and ps.qty > 1';
      } else if ( $trimmedFilter == 'Kadaluarsa'){
        $fText = 'showcaseexpiredat is not null';
      } else if($trimmedFilter == 'Habis'){
        $fText = 'showcaseexpiredat is null and ps.qty is null';
      }
      
      $q->whereRaw($fText);
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
      $q->orderByRaw("showcasedate desc");
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
        ->leftJoin('product_stock as ps', 'showcases.id', 'ps.stockshowcaseid')
        ->where('productactive', '1')
        ->where('showcaseactive', '1')
        ->where('showcases.id', $id)
        ->select(
          'showcasecode',
          'showcases.id as id',
          'showcaseproductid',
          'productname',
          'productprice',
          'productimg',
          'showcaseqty',
          DB::raw("to_char(showcasedate, 'dd-mm-yyyy') as showcasedate"),
          DB::raw("to_char(showcaseexpdate, 'dd-mm-yyyy') as showcaseexpdate"),
          'showcasestatus',
          DB::raw("to_char(showcasecreatedat, 'dd-mm-yyyy hh:mi') as showcasecreatedat"),
          'cr.username as showcasecreatedby',
          DB::raw("to_char(showcasemodifiedat, 'dd-mm-yyyy hh:mi') as showcasemodifiedat"),
          'mod.username as showcasemodifiedby',
          DB::raw("to_char(showcasesoldat, 'dd-mm-yyyy hh:mi') as showcasesoldat"),
          'sold.username as showcasesoldby',
          DB::raw("to_char(showcaseexpiredat, 'dd-mm-yyyy hh:mi') as showcaseexpiredat"),
          'exp.username as showcaseexpiredby',
          DB::raw("case when showcaseexpiredat is not null then 'Kadaluarsa' when showcaseexpiredat is null and ps.qty > 1  then 'ReadyStock' when showcaseexpiredat is null and ps.qty is null then 'Habis' end as status"),
          )
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
    $data = Showcase::where('showcaseactive', '1')
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
      ? array_push($respon['messages'], trans('fields.showcase'). ' Berhasil Dihapus.')
      : array_push($respon['messages'], trans('fields.showcase'). ' Tidak Ditemukan.');
    
    return $respon;
  }

  public static function expired($respon, $id, $loginid, $inputs)
  {
    $data = Showcase::where('showcaseactive', '1')
    ->where('id', $id)
    ->first();

    $cekDelete = false;

    if ($data != null){
      $data->update([
        'showcaseexpiredby' => $loginid,
        'showcaseexpiredat' => now()->toDateTimeString(),
        'showcaseexpiredqty' => $inputs["expiredqty"]
      ]);
      
      $cekDelete = true;
    }

    $respon['status'] = $data != null && $cekDelete ? 'success': 'error';
    $data != null && $cekDelete
      ? array_push($respon['messages'], trans('fields.showcase'). ' Berhasil Diupdate.')
      : array_push($respon['messages'], ' Error Kesalahan.');
    
    return $respon;
  }

  public static function getFields($model)
  {
    $model->id = null;
    $model->showcasecode = null;
    $model->showcaseproductid = null;
    $model->productname= null;
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
    $model->status= null;

    return $model;
  }
  
}