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

    'meja_lihat',
    'meja_simpan',
    'meja_hapus',    

    'menu_lihat',
    'menu_simpan',
    'menu_hapus',

    'order_lihat',
    'order_lihatBungkus',
    'order_simpan',
    'order_hapus',
    'order_batal',
    'order_pembayaran',
    'order_pelayan',

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

    'shift_lihat',
    'shift_simpan',
    'shift_tutup',
    'shift_hapus',
    'shift_detail',

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