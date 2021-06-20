<?php
namespace App\Repositories;

use App\Models\ProductCategory;
use DB;

class ProductCategoryRepository
{
  public static function save($respon, $inputs, $loginid)
  {
    $respon['state_code'] = 500;
    $q = ProductCategory::where('pcactive', '1')
      ->where('pcname', 'like', '%'. $inputs['pcname'] .'%')
      ->first();
    
    if($q != null){
      $respon['msg'] = 'Kategori sudah tersedia.';
    } else {
      $data = ProductCategory::create([
        'pcname' => $inputs['pcname'],
        'pcactive' => '1',
        'pccreatedat' => now()->toDateTimeString(),
        'pccreatedby' => $loginid
      ]);
      $respon['state_code'] = 200;
      $respon['status'] = 'success';
      $respon['msg'] = 'Kategori menu berhasil ditambah.';
    }
    return $respon;
  }

  public static function delete($respon, $id, $loginid)
  {
    $respon['state_code'] = 500;
    $q = ProductCategory::where('pcactive', '1')
      ->where('id', $id)
      ->first();
    if(q == null){
      $respon['msg'] = 'Kategori tidak tersedia.';
    } else {
      $q->update([
        'pcactive' => '0',
        'pcmodifiedat' => now()->toDateTimeString(),
        'pcmodifiedby' => $loginid
      ]);
      $respon['state_code'] = 200;
      $respon['status'] = 'success';
      $respon['msg'] = 'Kategori menu berhasil dihapus.';
    }

    return $respon;
  }

  public static function search($cari)
  {
    return ProductCategory::whereRaw('UPPER(pcname) LIKE UPPER(\'%'. $cari .'%\')')
    ->where('pcactive', '1')
    ->orderBy('pcname', 'ASC')
    ->select('id', 'pcname as text')
    ->get();
  }
}