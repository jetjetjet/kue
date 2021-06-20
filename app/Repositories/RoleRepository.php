<?php
namespace App\Repositories;

use DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use App\Models\Role;
use App\Models\UserRoles;

class RoleRepository
{
  public static function grid($perms)
  {
    return Role::where('roleactive', '1')
      ->select(
        'id',
        'rolename',
        'roleisadmin',
        'roledetail',        
        DB::raw($perms['save']),
        DB::raw($perms['delete']))
      ->get();
  }

  public static function get($respon, $id)
  {
    $data = new \stdClass();
    $respon['data'] = self::getFields($data);

    if($id){
      $respon['data'] = Role::where('roleactive', '1')
      ->where('id', $id)
      ->select(
        'id',
        'roleisadmin',
        'rolename',
        'roledetail',
        'rolepermissions')
      ->first();

      if($respon['data'] == null){
        $respon['status'] = 'error';
        array_push($respon['messages'],'Data Jabatan tidak ditemukan!');
      }

      //User Role
      $subs = UserRoles::where('uractive', '1')
        ->join('users', 'users.id', 'uruserid')
        ->join('roles', 'roles.id', 'urroleid')
        ->where('urroleid', $id)->select('uruserid')->get();
      //push userid -> role->userid
      $is = Array();
      foreach($subs as $sub){
        array_push($is, $sub['uruserid']);
      }
      $respon['data']->rolepermissions = explode(",",$respon['data']->rolepermissions);
      $respon['data']->userid = $is;
    }
    return $respon;
  }

  public static function save($respon, $inputs, $loginid)
  {
    $id = $inputs['id'] ?? 0;
    $perm = !empty($inputs['rolepermissions']) ? $inputs['rolepermissions'] : array();
    $inputs['perm'] = implode(",", $perm);
    $inputs['roleisadmin'] = isset($inputs['roleisadmin']) ? '1' : '0';
    try{
      DB::transaction(function () use (&$respon, $id, $inputs, $loginid){
        $respon = self::saveRole($respon, $id, $inputs, $loginid);
        if (!$respon['success']) return $respon;

        if($id != null){
          $valid = self::removeMissingUserRole($respon, $id, $inputs, $loginid);
        }

        $valid = self::saveUserRole($respon, $id, $inputs, $loginid);
        if (!$valid) return $respon;

        $respon['status'] = 'success';
      });
    }catch(\Exception $e){
      $eMsg = $e->getMessage() ?? "NOT_RECORDED";
      Log::channel('errorKape')->error("RoleSave_" .trim($eMsg));
      $respon['status'] = 'error';
      array_push($respon['messages'], 'Kesalahan! tidak dapat memproses perintah.');
    }
    $respon['id'] = $id;
    return $respon;
  }

  public static function delete($respon, $id, $loginid)
  {
    $data = Role::where('roleactive', '1')
      ->where('id', $id)
      ->first();

    $cekDelete = false;

    if ($data != null){
      $data->update([
        'roleactive' => '0',
        'rolemodifiedby' => $loginid,
        'rolemodifiedat' => now()->toDateTimeString()
      ]);
      
      //Delete UserRoles
      $sub= UserRoles::where('uractive', '1')
        ->where('urroleid', $id)
        ->update([
          'uractive' => '0',
          'urmodifiedby' => $loginid,
          'urmodifiedat' => now()->toDateTimeString()
        ]);

      $cekDelete = true;
    }

    $respon['status'] = $data != null && $cekDelete ? 'success': 'error';
    $data != null && $cekDelete
      ? array_push($respon['messages'], 'Data Jabatan berhasil dihapus.') 
      : array_push($respon['messages'], 'Data Jabatan tidak ditemukan');
    
    return $respon;
  }

  public static function saveRole($respon, $id, $inputs, $loginid)
  {
    $respon['success'] = false;
    $role = null;
    if($id == null){
      $role = Role::create([
        'rolename' => $inputs['rolename'],
        'roledetail' => $inputs['roledetail'],
        'rolepermissions' => $inputs['perm'],
        'roleisadmin' => $inputs['roleisadmin'],
        'roleactive' => '1',
        'rolecreatedat' => now()->toDateTimeString(),
        'rolecreatedby' => $loginid
      ]);
      array_push($respon['messages'], 'Data Jabatan berhasil ditambah.');
    } else {
      $role = Role::where('roleactive', '1')->where('id', $id)->firstOrFail();
      $role->update([
        'rolename' => $inputs['rolename'],
        'roledetail' => isset($inputs['roledetail']) ? $inputs['roledetail'] :null,
        'rolepermissions' => $inputs['perm'],
        'roleisadmin' => $inputs['roleisadmin'],
        'rolemodifiedat' => now()->toDateTimeString(),
        'rolemodifiedby' => $loginid
      ]);
      array_push($respon['messages'], 'Data Jabatan berhasil diubah.');
    }
    $respon['roleid'] = $role->id ?? $id;
    $respon['data'] = $role;
    $respon['success'] = true;
    return $respon;
  }

  public static function removeMissingUserRole(&$respon, $id, $inputs, $loginId)
  {
    $inputs['userid'] = isset($inputs['userid']) ? $inputs['userid'] : array();
    $data = UserRoles::where('uractive', '1')
      ->where('urroleid', $id)
      ->whereNotIn('uruserid', $inputs['userid'])
      ->update([
        'uractive' => '0',
        'urmodifiedby' => $loginId,
        'urmodifiedat' => now()->toDateTimeString()
        ]);
    return true;
  }

  public static function saveUserRole(&$respon, $id, $inputs, $loginId)
  {
    $idusers = isset($inputs['userid']) ? $inputs['userid'] : array();
    $nId = $respon['roleid'] ?? $id;
    foreach($idusers as $iduser){
      $userRole = UserRoles::where('urroleid', $nId)
        ->where('uruserid', $iduser)
        ->where('uractive', '1')
        ->first();
      if($userRole == null){
        UserRoles::create([
          'urroleid' => $nId,
          'uruserid' => $iduser,
          'uractive' => '1',
          'urcreatedby' => $loginId,
          'urcreatedat' =>now()->toDateTimeString()
        ]);
      }
    }
    return true;
  }


  public static function getFields($model)
  {
    $model->id = null;
    $model->rolename = null;
    $model->roleisadmin = false;
    $model->roledetail = null;
    $model->userid = [];
    $model->rolepermissions = null;

    return $model;
  }
}