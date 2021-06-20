<?php
namespace App\Repositories;

use App\Models\Customer;
use DB;

class CustomerRepository

{
    public static function grid()
    {
      return Customer::where('custactive', '1')->select('id','custname', DB::raw('left(customers.custaddress, 30) as custaddress'), 'custphone')->get();
    }
  
    public static function get($respon, $id)
    {
      $data = new \stdClass();
      $respon['data'] = Customer::getFields($data);
      if($id){
        $respon['data'] = Customer::where('custactive', '1')
        ->where('id', $id)
        ->select('id', 'custname', 'custaddress', 'custphone')
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
  
      $data = Customer::where('custactive', '1')
        ->where('id',$id)
        ->first();
      try{
        if ($data != null){
          $data = $data->update([
            'custname' => $inputs['custname'],
            'custaddress' => $inputs['custaddress'],
            'custphone' => $inputs['custphone'],
            'custmodifiedat' => now()->toDateTimeString(),
            'custmodifiedby' => $loginid
          ]);
  
          $respon['status'] = 'success';
          array_push($respon['messages'], 'Data Pelanggan berhasil diubah');
          
        } else {
          $data = Customer::create([
            'custname' => $inputs['custname'],
            'custaddress' => $inputs['custaddress'],
            'custphone' => $inputs['custphone'],
            'custactive' => '1',
            'custcreatedat' => now()->toDateTimeString(),
            'custcreatedby' => $loginid
          ]);
  
          $respon['status'] = 'success';
          array_push($respon['messages'], 'Data Pelanggan berhasil ditambah');
        }
      } catch(\Exception $e){
        dd($e);
        $respon['status'] = 'error';
        array_push($respon['messages'], 'Error');
      }
      $respon['id'] = ($data->id ?? $inputs['id']) ?? null;
      return $respon;
    }
  
    public static function delete($respon, $id, $loginid)
    {
      $data = Customer::where('custactive', '1')
        ->where('id', $id)
        ->first();
  
      $cekDelete = false;
  
      if ($data != null){
        $data->update([
          'custactive' => '0',
          'custmodifiedby' => $loginid,
          'custmodifiedat' => now()->toDateTimeString()
        ]);
        
        $cekDelete = true;
      }
  
      $respon['status'] = $data != null && $cekDelete ? 'success': 'error';
      $data != null && $cekDelete
        ? array_push($respon['messages'], 'Data Pelanggan Berhasil Dihapus.') 
        : array_push($respon['messages'], 'Data Pelanggan Tidak Ditemukan');
      
      return $respon;
    }
  
  }