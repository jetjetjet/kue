<?php
namespace App\Repositories;

use App\Models\Promo;
use App\Models\SubPromo;
use Illuminate\Support\Facades\Log;

use Exception;
use DB;

class PromoRepository
{
  public static function grid($perms)
  {
    return Promo::where('promoactive', '1')
    ->orderBy('promostart', 'DESC')
    ->select(
      'id',
      'promoname',
      'promodetail',
      DB::raw("to_char(promostart, 'dd-mm-yyyy HH24:MI') as promostart"),
      DB::raw("to_char(promoend, 'dd-mm-yyyy HH24:MI') as promoend"),
      'promodiscount',
      DB::raw("case when now()::timestamp without time zone < promoend::timestamp without time zone then 'Aktif' else 'Kadaluarsa' end as promostatus"),
      DB::raw("case when now()::timestamp without time zone < promoend::timestamp without time zone and " .$perms['save'] . " = 1 then true else false end as can_edit"),
      DB::raw("case when now()::timestamp without time zone < promoend::timestamp without time zone and " .$perms['delete'] . " = 1 then true else false end as can_delete")
      )
    ->get();
  }

  public static function get($respon, $id)
  {
    if($id){
      $header = Promo::where('promoactive', '1')
        ->where('promo.id', $id)
        ->select(
          'id',
          'promoname',
          'promodetail',
          DB::raw("to_char(promostart, 'dd-mm-yyyy HH24:MI:SS') as promostart"),
          DB::raw("to_char(promoend, 'dd-mm-yyyy HH24:MI:SS') as promoend"),
          DB::raw("case when now()::timestamp without time zone > promoend::timestamp without time zone then false else true end as editable"),
          'promodiscount')
        ->first();
      if($header == null){
        $respon['status'] = 'error';
        array_push($respon['messages'],'Data tidak ditemukan!');

        return $respon;
      }

      $header->sub = DB::table('subpromo as sp')
        ->join('menus as m', 'm.id', 'spmenuid')
        ->join('menucategory as mc', 'mc.id', 'menumcid')
        ->where('sppromoid', $header->id)
        ->where('spactive', '1')
        ->where('menuactive', '1')
        ->select(
          'sp.id',
          'spmenuid',
          'spindex',
          'menuname',
          'mcname as menucategory',
          'menutype',
          'menuavaible',
          'menuprice',
          DB::raw("menuprice - ". $header->promodiscount ." as menupromo "))
        ->get();
      $respon['data'] = $header;

    } else {
      $data = new \stdClass();
      $respon['data'] = self::getFields($data);
    }
    return $respon;
  }

  public static function save($respon, $inputs, $loginid)
  {
    $respon['success'] = false;
    $id = $inputs['id'] ;
    // $sub = $inputs['sub'];

    try{
      DB::transaction(function () use (&$respon, $id, $inputs, $loginid)
      {
        $valid = self::savePromo($respon, $id, $inputs, $loginid);
        if (!$valid['success']) return $respon;

        if($id != null){
          $valid = self::removeMissingDetails($respon, $id, $inputs['sub'], $loginid);
          if (!$valid['success']) return $respon;
        }

        $valid = self::saveSubPromo($respon, $id, $inputs['sub'], $loginid);
        if (!$valid['success']) return $respon;
        $respon['status'] = 'success';
        $respon['success'] = true;
      });
    } catch (\Exception $e) {
      $eMsg = $e->getMessage() ?? "NOT_RECORDED";
      Log::channel('errorKape')->error("PromoSave_" .trim($eMsg));
      
      $respon['messages'] = $e->getMessage() == "emptysubmenu" 
        ? ["Gagal menyimpan Promo! Terdapat Sub Menu kosong"]
        : ["Gagal menyimpan Promo!"];
      
      $respon['status'] = "error";
    }
    return $respon;
  }

  private static function savePromo(&$respon, $id, $inputs, $loginid)
  {
    $data = "";
    $respon['success'] = false;
    if($id == null){
      $data = Promo::create([
        'promoname' => $inputs['promoname'],
        'promodetail' => $inputs['promodetail'],
        'promostart' => $inputs['promostart'].":00",
        'promoend' => $inputs['promoend'] .":00",
        'promodiscount' => $inputs['promodiscount'],
        'promoactive' => '1',
        'promocreatedat' => now()->toDateTimeString(),
        'promocreatedby' => $loginid
      ]);

      if($data->id != null){
        $respon['id'] = $data->id;
        $respon['success'] = true;
        $respon['messages'] = ['Promo berhasil ditambah'];
        // array_push($respon['messages'], 'Promo berhasil ditambah');
      } else {
        throw new Exception('error_save');
      }
    } else {
      $data = Promo::where('promoactive', '1')
        ->where('id',$id)
        ->update([
          'promoname' => $inputs['promoname'],
          'promodetail' => $inputs['promodetail'],
          'promostart' => $inputs['promostart'],
          'promoend' => $inputs['promoend'],
          'promodiscount' => $inputs['promodiscount'],
          'promomodifiedat' => now()->toDateTimeString(),
          'promomodifiedby' => $loginid]);
      if($data >= 1){
        $respon['id'] = $id;
        $respon['success'] = true;
      } else {
        throw new Exception('error_update');
      }
      
      $respon['messages'] = ['Promo berhasil diubah.'];
      // array_push($respon['messages'], 'Promo berhasil diubah.');
    }
    return $respon;
  }

  private static function removeMissingDetails(&$respon, $id, $details, $loginid)
  {
    $ids = Array();
    $respon['success'] = true;
    foreach($details as $dt){
      array_push($ids,$dt->id != null ? $dt->id : 0);
    }

    $data = SubPromo::where('spactive', '1')
      ->where('sppromoid', $id)
      ->whereNotIn('id', $ids)
      ->update([
        'spactive' => '0',
        'spmodifiedby' => $loginid,
        'spmodifiedat' => now()->toDateTimeString()
        ]);

    if($data >= 0){
      $respon['id'] = $id;
      $respon['success'] = true;
    } else {
      throw new Exception('error_deleteRows');
    }
    
    return $respon;
  }

  private static function saveSubPromo(&$respon, $id, $subs, $loginid)
  {
    $idHeader = $id != null ? $id : $respon['id'];
    $detRow = "";
    $respon['success'] = false;

    foreach ($subs as $sub){
      if(isset($sub->spmenuid)){
        if (!isset($sub->id)){
          $detRow = SubPromo::create([
            'sppromoid' => $idHeader,
            'spmenuid' => $sub->spmenuid,
            'spindex' => $sub->index,
            'spactive' => '1',
            'spcreatedat' => now()->toDateTimeString(),
            'spcreatedby' => $loginid
          ]);

          if($detRow == null){
            throw new Exception('error_savesub');
            return $respon;
          }
        } else {
          $detRow = SubPromo::where('spactive', '1')
            ->where('id', $sub->id)
            ->update([
              'spmenuid' => $sub->spmenuid,
              'spindex' => $sub->index,
              'spmodifiedat' => now()->toDateTimeString(),
              'spmodifiedby' => $loginid]);

          if($detRow >= 1){
            $respon['success'] = true;
          } else {
            throw new Exception('error_update');
            return $respon;
          }
        }
      } else {
        throw new Exception('emptysubmenu');
      }
    }
    
    $respon['success'] = true;
    return $respon;
  }

  public static function delete($respon, $id, $loginid)
  {
    $data = Promo::where('promoactive', '1')
      ->where('id', $id)
      ->first();

    $cekDelete = false;

    if ($data != null){
      $data->update([
        'promoactive' => '0',
        'promomodifiedby' => $loginid,
        'promomodifiedat' => now()->toDateTimeString()
      ]);

      $detRow = SubPromo::where('spactive', '1')
        ->where('sppromoid', $id)
        ->update([
          'spactive' => '0',
          'spmodifiedat' => now()->toDateTimeString(),
          'spmodifiedby' => $loginid]);
      
      $cekDelete = true;
    }

    $respon['status'] = $data != null && $cekDelete ? 'success': 'error';
    $data != null && $cekDelete
      ? array_push($respon['messages'], 'Promo Berhasil Dihapus.') 
      : array_push($respon['messages'], 'Promo Tidak Ditemukan');
    
    return $respon;
  }

  public static function deleteSub($respon, $id, $loginid)
  {
    $data = SubPromo::where('spactive', '1')
      ->where('id', $id)
      ->first();

    $cekDelete = false;

    if ($data != null){
      $data->update([
        'spactive' => '0',
        'spmodifiedat' => now()->toDateTimeString(),
        'spmodifiedby' => $loginid
      ]);

      $cekDelete = true;
    }

    $respon['status'] = $data != null && $cekDelete ? 'success': 'error';
    $data != null && $cekDelete
      ? array_push($respon['messages'], 'Menu Promo Berhasil Dihapus.') 
      : array_push($respon['messages'], 'Menu Promo Tidak Ditemukan');
    
    return $respon;
  }

  public static function getFields($db)
  {
    $db->id = null;
    $db->menucategory = null;
    $db->menuname = null;
    $db->menutype = null;
    $db->menuimg = null;
    $db->menuprice = null;
    $db->promoprice = null;
    $db->menuavaible = null;
    $db->promoname = null;
    $db->promodetail = null;
    $db->promostart = null;
    $db->promoend = null;
    $db->promodiscount = null;
    $db->editable = true;

    $db->sub = Array();

    return $db;
  }
}