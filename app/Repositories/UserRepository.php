<?php
namespace App\Repositories;

use DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use App\Models\User;

class UserRepository
{
  public static function grid($perms)
  {
    $sub = DB::table('userroles as ur')
      ->join('roles as r', 'r.id', 'urroleid')
      ->where('uractive', '1')
      ->where('roleactive', '1')
      ->groupBy('uruserid')
      ->select('uruserid', DB::raw("string_agg(rolename,', ') as roles"));

    return User::where('useractive', '1')
      ->leftJoinSub($sub, 'sub', function($q){
        $q->on('users.id', 'sub.uruserid');
      })
      ->select(
        'users.id',
        'userfullname',
        'username',
        'usercontact',
        'sub.roles',
        DB::raw($perms['save']),
        DB::raw($perms['delete']))
      ->get();
  }

  public static function get($respon, $id)
  {
    $data = new \stdClass();
    $respon['data'] = self::getFields($data);

    if($id){
      $respon['data'] = User::where('useractive', '1')
      ->where('id', $id)
      ->select(
        'id',
        'userfullname',
        'username',
        'usercontact',
        'useraddress',
        DB::raw("to_char(userjoindate, 'dd-mm-yyyy') as userjoindate"))
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
    $username = $inputs['username'];

    $data = User::where('useractive', '1')
      ->where(function($q) use($id, $username){
        $q->where('id', $id)
          ->orWhere('username', $username);
     })->first();

    try{
      if ($data != null){
        $data = $data->update([
          'userfullname' => $inputs['userfullname'],
          'usercontact' => $inputs['usercontact'],
          'useraddress' => $inputs['useraddress'],
          'userjoindate' => $inputs['userjoindate'],
          'usermodifiedat' => now()->toDateTimeString(),
          'usermodifiedby' => $loginid
        ]);

        $respon['status'] = 'success';
        array_push($respon['messages'], 'Data User berhasil diubah.');
        
      } else {
        $data = User::create([
          'username' => $inputs['username'],
          'userpassword' => Hash::make($inputs['userpassword']),
          'userfullname' => $inputs['userfullname'],
          'usercontact' => $inputs['usercontact'],
          'useraddress' => $inputs['useraddress'],
          'userjoindate' => $inputs['userjoindate'],
          'useractive' => '1',
          'usercreatedat' => now()->toDateTimeString(),
          'usercreatedby' => $loginid
        ]);

        $respon['status'] = 'success';
        array_push($respon['messages'], 'Data User berhasil ditambah.');
      }
    } catch(\Exception $e){
      $eMsg = $e->getMessage() ?? "NOT_RECORDED";
      Log::channel('errorKape')->error("UserSave_" .trim($eMsg));
      $respon['status'] = 'error';
      array_push($respon['messages'], 'Kesalahan! tidak dapat memproses perintah.');
    }
    $respon['id'] = ($data->id ?? $inputs['id']) ?? null;
    return $respon;
  }

  public static function delete($respon, $id, $loginid)
  {
    if ($id == 1){
      array_push($respon['messages'], 'User superadmin tidak bisa dihapus.');
      $respon['status'] = 'error';

    }else{
      $data = User::where('useractive', '1')
        ->where('id', $id)
        ->first();

      $cekDelete = false;

      if ($data != null){
        $data->update([
          'useractive' => '0',
          'usermodifiedby' => $loginid,
          'usermodifiedat' => now()->toDateTimeString()
        ]);
        
        $cekDelete = true;
      }

      $respon['status'] = $data != null && $cekDelete ? 'success': 'error';
      $data != null && $cekDelete
        ? array_push($respon['messages'], 'Data User berhasil dihapus.') 
        : array_push($respon['messages'], 'Data User tidak ditemukan');
    }
    return $respon;
  }

  public static function changePassword($respon, $id, $inputs, $loginid)
  {
    $data = User::where('useractive', '1')
      ->where('id', $id)
      ->first();

    $cekUpdate = false;

    if ($data != null){
      $data->update([
        'userpassword' => Hash::make($inputs['userpassword']),
        'usermodifiedby' => $loginid,
        'usermodifiedat' => now()->toDateTimeString()
      ]);
      
      $cekUpdate = true;
    }
  
    $respon['status'] = $data != null && $cekUpdate ? 'success': 'error';
    $data != null && $cekUpdate
      ? array_push($respon['messages'], 'Password berhasil diubah.') 
      : array_push($respon['messages'], 'Password gagal diubah.');
    
    return $respon;
  }

  public static function userActive()
  {
    return User::where('useractive', '1');
  }

  public static function search($cari)
  {
    return User::whereRaw('UPPER(username) LIKE UPPER(\'%'. $cari .'%\')')
      ->where('useractive', '1')
      ->select('id', 'username')
      ->get();
  }

  public static function getFields($model)
  {
    $model->id = null;
    $model->userfullname = null;
    $model->username = null;
    $model->userpassword = null;
    $model->usercontact = null;
    $model->useraddress = null;
    $model->userjoindate = null;
    // $model->useractive = null;
    // $model->usercreatedat = null;
    // $model->usercreatedby = null;
    // $model->usermodifiedat = null;
    // $model->usermodifiedby = null;

    return $model;
  }
}