<?php
namespace App\Repositories;

use App\Models\Menu;
use Illuminate\Support\Facades\Log;
use DB;

class MenuRepository
{
  public static function grid($perms)
  {
    return Menu::where('menuactive', '1')
    ->join('menucategory', 'menus.menumcid', '=', 'menucategory.id')
    ->select('menus.id',
      'menuname', 
      'menutype', 
      'menuprice', 
      'mcname',
      DB::raw("CASE WHEN menus.menuavaible = true THEN 'Tersedia' ELSE 'Kosong' END as menuavaible"),
      DB::raw($perms['save']),
      DB::raw($perms['delete']))
    ->get();
  }

  public static function get($respon, $id)
  {
    $data = new \stdClass();
    $respon['data'] = self::getFields($data);

    $getId = Menu::select('id')->orderBy('id', 'DESC')->first();
    $dId = $getId->id??null;
    $respon['data']->getId = $dId + '1';

    if($id){
      $respon['data'] = Menu::join('menucategory as mc', 'mc.id', 'menumcid')
      ->where('mcactive', '1')
      ->where('menuactive', '1')
      ->where('menus.id', $id)
      ->select(
        'menus.id',
        'menumcid',
        'mcname as menumcname',
        'menuname', 
        'menutype', 
        'menuprice',
        'menudetail',
        'menuimg',
        'menuavaible')
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
    $data = Menu::where('menuactive', '1')
      ->where('id',$id)
      ->first();
    try{
      if ($data != null){
        $data = $data->update([
          'menumcid' => $inputs['menumcid'],
          'menuname' => $inputs['menuname'],
          'menutype' => $inputs['menutype'],
          'menuimg' => $inputs['menuimgpath'],
          'menudetail' => $inputs['menudetail'],
          'menuprice' => $inputs['menuprice'],
          'menuavaible' => $inputs['menuavaible']??'0',
          'menumodifiedat' => now()->toDateTimeString(),
          'menumodifiedby' => $loginid
        ]);

        $respon['status'] = 'success';
        array_push($respon['messages'], 'Data Menu berhasil diubah');
        
      } else {
        $data = Menu::create([
          'menumcid' => $inputs['menumcid'],
          'menuname' => $inputs['menuname'],
          'menutype' => $inputs['menutype'],
          'menuimg' => $inputs['menuimgpath'],
          'menudetail' => $inputs['menudetail'],
          'menuprice' => $inputs['menuprice'],
          'menuavaible' => $inputs['menuavaible']??'0',
          'menuactive' => '1',
          'menucreatedat' => now()->toDateTimeString(),
          'menucreatedby' => $loginid
        ]);

        $respon['status'] = 'success';
        array_push($respon['messages'], 'Data Menu berhasil ditambah');
      }
    } catch(\Exception $e){
      $eMsg = $e->getMessage() ?? "NOT_RECORDED";
      Log::channel('errorKape')->error("MenuSave_" .trim($eMsg));
      $respon['status'] = 'error';
      array_push($respon['messages'], 'Error');
    }
    $respon['id'] = ($data->id ?? $inputs['id']) ?? null;
    return $respon;
  }

  public static function delete($respon, $id, $loginid)
  {
    $data = Menu::where('menuactive', '1')
      ->where('id', $id)
      ->first();

    $cekDelete = false;

    if ($data != null){
      $data->update([
        'menuactive' => '0',
        'menumodifiedby' => $loginid,
        'menumodifiedat' => now()->toDateTimeString()
      ]);
      
      $cekDelete = true;
    }

    $respon['status'] = $data != null && $cekDelete ? 'success': 'error';
    $data != null && $cekDelete
      ? array_push($respon['messages'], 'Menu Berhasil Dihapus.') 
      : array_push($respon['messages'], 'Menu Tidak Ditemukan');
    
    return $respon;
  }

  public static function topMenu($filters)
  {
    $detailOrder = DB::table('orderdetail')
      ->where('odactive', '1')
      ->groupBy('odmenuid')
      ->select(
        DB::raw(" sum(odqty) as totalorder"),
        'odmenuid');
      
    if($filters){
      foreach($filters as $f)
      {
        $detailOrder = $detailOrder->whereRaw($f);
      }
    }
    $data = Menu::joinSub($detailOrder, 'od', function ($join) {
        $join->on('menus.id', '=', 'od.odmenuid');})
      ->select(
        'menuname',
        'menuprice',
        'od.totalorder')
      ->orderBy('od.totalorder', 'DESC')->limit(10)->get();

    return $data;
  }

  public static function menuapi($respon)
  {
    $tempdata = Array('Makanan'=>Array(), 'Minuman'=>Array());
    $getCat = Menu::join('menucategory as mc', 'mc.id', 'menumcid')
      ->where('mcactive', '1')
      ->where('menuactive', '1')
      ->select('menuname', 'menuimg', 'menuprice', 'menuavaible', 'menutype')
      ->get();

    foreach($getCat as $data )
    {
      if($data->menutype == 'Makanan'){
      array_push($tempdata['Makanan'], $data);
      }else if($data->menutype == 'Minuman'){
        array_push($tempdata['Minuman'], $data);
      }
    }
    $respon['status'] = 'success';
    $respon['data'] = $tempdata;

    return $respon;
  }

  public static function getMenu()
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
        'spmenuid',
        'promoname',
        'promodiscount',
        DB::raw("to_char(promostart, 'dd-mm-yyyy HH24:MI:SS') as promostart"),
        DB::raw("to_char(promoend, 'dd-mm-yyyy HH24:MI:SS') as promoend"),
      );

    $getCat = Menu::join('menucategory as mc', 'mc.id', 'menumcid')
      ->leftJoinSub($promo, 'promo', function ($join) {
        $join->on('menus.id', '=', 'promo.spmenuid');
      })
      ->where('mcactive', '1')
      ->where('menuactive', '1')
      ->orderBy('menuname', 'ASC')
      ->select(
        'menus.id',
        'menuname', 
        'menuimg', 
        'menuprice as menupriceraw', 
        'menuavaible', 
        'menutype', 
        'menumcid', 
        'mcname',
        'promoname',
        'promodiscount',
        'promostart',
        'promoend',
        'promoid',
        DB::raw("(menuprice - COALESCE(promodiscount, 0)) as menuprice"))
      ->get();
    foreach($getCat as $data )
    {
      if($data->menutype == 'Makanan'){
        if(!isset($tempdata['Makanan'][$data->mcname])){
          $tempdata['Makanan'][$data->mcname] = Array();
          $tempdata['Makanan'][$data->mcname]['nama'] = $data->mcname;
          $tempdata['Makanan'][$data->mcname]['pilihan'] = Array();
        }
        array_push($tempdata['Makanan'][$data->mcname]['pilihan'], $data);
      }else if($data->menutype == 'Minuman'){
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
    $model->menuname = null;
    $model->menutype = null;
    $model->menumcid = null;
    $model->menumcname = null;
    //$model->userid = [];
    $model->menuprice = null;
    $model->menudetail = null;
    $model->menuimg = null;
    $model->menuavaible= null;

    return $model;
  }

  public static function search($cari)
  {
    $promo = self::searchPromo();
    
    return Menu::join('menucategory as mc', 'mc.id', 'menumcid')
      ->leftJoinSub($promo, 'promo', function ($join) {
        $join->on('menus.id', '=', 'promo.spmenuid');
      })
      ->whereRaw('UPPER(menuname) LIKE UPPER(\'%'. $cari .'%\')')
      ->where('menuactive', '1')
      ->where('menuavaible', '1')
      ->whereNull('promoid')
      ->select('menus.id', 'mcname as menucategory', 'menuname as text', 'menutype', 'menuprice')
      ->orderby('menuname', 'ASC')
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
        'spmenuid',
        'promodiscount'
      );

  }
}