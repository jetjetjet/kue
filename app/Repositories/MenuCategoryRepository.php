<?php
namespace App\Repositories;

use App\Models\MenuCategory;
use DB;

class MenuCategoryRepository
{
  public static function save($respon, $inputs, $loginid)
  {
    $respon['state_code'] = 500;
    $q = MenuCategory::where('mcactive', '1')
      ->where('mcname', 'like', '%'. $inputs['mcname'] .'%')
      ->first();
    
    if($q != null){
      $respon['msg'] = 'Kategori sudah tersedia.';
    } else {
      $data = MenuCategory::create([
        'mcname' => $inputs['mcname'],
        'mcactive' => '1',
        'mccreatedat' => now()->toDateTimeString(),
        'mccreatedby' => $loginid
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
    $q = MenuCategory::where('mcactive', '1')
      ->where('id', $id)
      ->first();
    if(q == null){
      $respon['msg'] = 'Kategori tidak tersedia.';
    } else {
      $q->update([
        'mcactive' => '0',
        'mcmodifiedat' => now()->toDateTimeString(),
        'mcmodifiedby' => $loginid
      ]);
      $respon['state_code'] = 200;
      $respon['status'] = 'success';
      $respon['msg'] = 'Kategori menu berhasil dihapus.';
    }

    return $respon;
  }

  public static function search($cari)
  {
    return MenuCategory::whereRaw('UPPER(mcname) LIKE UPPER(\'%'. $cari .'%\')')
    ->where('mcactive', '1')
    ->orderBy('mcname', 'ASC')
    ->select('id', 'mcname as text')
    ->get();
  }
}