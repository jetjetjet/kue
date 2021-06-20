<?php
namespace App\Repositories;

use App\Models\Setting;
use Illuminate\Support\Facades\Log;
use DB;

class SettingRepository

{
    public static function grid($perms)
    {
      return Setting::where('settingactive', '1')->select('id','settingcategory','settingkey', DB::raw('left(settings.settingvalue, 30) as settingvalue'),DB::raw($perms['save']))->get();
    }
  
    public static function get($respon, $id)
    {
      $data = new \stdClass();
      $respon['data'] = Setting::getFields($data);
      if($id){
        $respon['data'] = Setting::where('settingactive', '1')
        ->where('id', $id)
        ->select('id', 'settingcategory', 'settingkey', 'settingvalue')
        ->first();
  
        if($respon['data'] == null){
          $respon['status'] = 'error';
          array_push($respon['messages'],'Data tidak ditemukan!');
        }
      }
      return $respon;
    }

    public static function getAppSetting($filter)
    {
      $q = Setting::where('settingactive', '1')
        ->where('settingkey', $filter)
        ->select('settingvalue')
        ->first();
      return $q->settingvalue ?? null;
    }
  
    public static function save($respon, $inputs, $loginid)
    {
      $id = $inputs['id'] ?? 0;
  
      $data = Setting::where('settingactive', '1')
        ->where('id',$id)
        ->first();
      try{
          $data = $data->update([
            'settingcategory' => $inputs['settingcategory'],
            'settingkey' => $inputs['settingkey'],
            'settingvalue' => $inputs['settingvalue'],
            'settingmodifiedat' => now()->toDateTimeString(),
            'settingmodifiedby' => $loginid
          ]);
  
          $respon['status'] = 'success';
          array_push($respon['messages'], 'Pengaturan berhasil diubah');
      
      } catch(\Exception $e){
        $eMsg = $e->getMessage() ?? "NOT_RECORDED";
        Log::channel('errorKape')->error("SettingSave_" .trim($eMsg));
        $respon['status'] = 'error';
        array_push($respon['messages'], 'Error');
      }
      $respon['id'] = ($data->id ?? $inputs['id']) ?? null;
      return $respon;
    }
  
    public static function saveInitApp($respon, $inputs)
    {
      try{
        foreach($inputs as $key=>$input){
          if ($key == '_token')
            continue;
          
          if($key == 'logoApp'){
            $path = "\images\\";
            $cr = Setting::create([
              'settingcategory' => 'AppLogo',
              'settingkey' => $key,
              'settingvalue' => $path . $input->newName,
              'settingactive' => '1',
              'settingcreatedat' => now()->toDateTimeString(),
              'settingcreatedby' => '0'
            ]);
          } else {
            $cr = Setting::create([
              'settingcategory' => 'AppSetting',
              'settingkey' => $key,
              'settingvalue' => $input,
              'settingactive' => '1',
              'settingcreatedat' => now()->toDateTimeString(),
              'settingcreatedby' => '0'
            ]);
          }
        }
        
        $respon['status'] = 'success';
        array_push($respon['messages'], 'Setting Aplikasi berhasil.');
      }catch(\Exception $e){
        $eMsg = $e->getMessage() ?? "NOT_RECORDED";
        Log::channel('errorKape')->error("InitApp_" .trim($eMsg));
        $respon['status'] = 'error';
        array_push($respon['messages'], 'Error');
      }
      return $respon;
    }
  }