<?php
namespace App\Libs;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

use App\Models\User;

use DB;

class AuthCafe
{
  public static $all = array(
    'jabatan_lihat',
    'jabatan_simpan',
    'jabatan_hapus',

    'laporan_lihat',
    'log_lihat',  

    'order_lihat',
    'order_simpan',
    'order_hapus',
    'order_batal',
    'order_pembayaran',
    'order_deleteDetail',
    'order_updatePesanan',

    'pengaturan_lihat',
    'pengaturan_edit',

    'pengeluaran_lihat',
    'pengeluaran_simpan',
    'pengeluaran_hapus',
    'pengeluaran_proses',
    'pengaturan_notif',

    'promo_lihat',
    'promo_simpan',
    'promo_hapus',

    'product_lihat',
    'product_simpan',
    'product_hapus',

    'showcase_lihat',
    'showcase_simpan',
    'showcase_hapus',
    'showcase_kadaluarsa',

    'user_lihat',
    'user_simpan',
    'user_hapus',

    'tambahan_bukalaci',
  );

  public static function all(){
    $result = array();
    foreach (self::$all as $value){
      $values = explode('_', $value);
      if (!isset($result[$values[0]])){
          $result[$values[0]] = new \stdClass();
          $result[$values[0]]->module = $values[0];
          $result[$values[0]]->actions = array();
      }
        
      $action = new \stdClass();
      $action->raw = $value;
      $action->value = $values[1];
      array_push($result[$values[0]]->actions, $action);
    }

    ksort($result);
    return $result;
  }

  public static function full($permissions){
    $maps = array_map(function ($value) use ($permissions){
      return in_array($value, $permissions);
    }, self::$all);
    $full = count(array_keys($maps, true)) === count(self::$all);
    return $full;
  }

  public static function admin()
  {
    if (Auth::user()->getAuthIdentifier() === 1) 
    return true;
  }

  public static function can($permissions)
  {
    if (Self::admin()) return true;
    return Auth::check() && Auth::user()->can($permissions,[]);
  }
}