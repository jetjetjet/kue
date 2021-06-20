<?php
namespace App\Repositories;

use App\Models\Expense;
use Illuminate\Support\Facades\Log;
use DB;

class ExpenseRepository

{
    public static function grid($perms)
    {
      return Expense::where('expenseactive', '1')->select('id','expensename', 'expenseprice', DB::raw("to_char(expensedate, 'DD-MM-YYYY')as expensedateraw"), 'expensedetail', 'expenseexecutedat', DB::raw($perms['save']), DB::raw($perms['delete']))->get();
    }
  
    public static function get($respon, $id)
    {
      $data = new \stdClass();
      $respon['data'] = Expense::getFields($data);
      if($id){
        $respon['data'] = Expense::where('expenseactive', '1')
        ->where('id', $id)
        ->select('id', 'expensename', 'expenseprice', DB::raw("to_char(expensedate, 'DD-MM-YYYY')as expensedateraw"), 'expensedetail', 'expenseactive', 'expenseexecutedat', 'expenseexecutedby','expensedate')
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
  
      $data = Expense::where('expenseactive', '1')
        ->where('id',$id)
        ->first();
      try{
        if ($data != null){
          $data = $data->update([
            'expensename' => $inputs['expensename'],
            'expenseprice' => $inputs['expenseprice'],
            'expensedate' => $inputs['expensedate'],
            'expensedetail' => $inputs['expensedetail'],
            'expensemodifiedat' => now()->toDateTimeString(),
            'expensemodifiedby' => $loginid
          ]);
  
          $respon['status'] = 'success';
          array_push($respon['messages'], 'Data Pengeluaran berhasil diubah');
          
        } else {
          $data = Expense::create([
            'expensename' => $inputs['expensename'],
            'expenseprice' => $inputs['expenseprice'],
            'expensedate' => $inputs['expensedate'],
            'expensedetail' => $inputs['expensedetail'],
            'expenseexecutedby' => '0',
            'expenseactive' => '1',
            'expensecreatedat' => now()->toDateTimeString(),
            'expensecreatedby' => $loginid
          ]);
  
          $respon['status'] = 'success';
          array_push($respon['messages'], 'Data Pengeluaran berhasil ditambah');
        }
      } catch(\Exception $e){
        $eMsg = $e->getMessage() ?? "NOT_RECORDED";
        Log::channel('errorKape')->error("ExpenseSave_".trim($eMsg));
        $respon['status'] = 'error';
        array_push($respon['messages'], 'Error');
      }
      $respon['id'] = ($data->id ?? $inputs['id']) ?? null;
      return $respon;
    }
  
    public static function delete($respon, $id, $loginid)
    {
      $data = Expense::where('expenseactive', '1')
        ->where('id', $id)
        ->first();
  
      $cekDelete = false;
  
      if ($data != null){
        $data->update([
          'expenseactive' => '0',
          'expensemodifiedby' => $loginid,
          'expensemodifiedat' => now()->toDateTimeString()
        ]);
        
        $cekDelete = true;
      }
  
      $respon['status'] = $data != null && $cekDelete ? 'success': 'error';
      $data != null && $cekDelete
        ? array_push($respon['messages'], 'Data Pengeluaran Berhasil Dihapus.') 
        : array_push($respon['messages'], 'Data Pengeluaran Tidak Ditemukan');
      
      return $respon;
    }

    public static function proceed($respon, $id, $loginid)
    {
      $data = Expense::where('expenseactive', '1')
        ->where('id', $id)
        ->first();
  
      $cekDelete = false;
  
      if ($data != null){
        $data->update([
          'expenseexecutedby' => $loginid,
          'expenseexecutedat' => now()->toDateTimeString()
        ]);
        
        $cekDelete = true;
      }
  
      $respon['status'] = $data != null && $cekDelete ? 'success': 'error';
      $data != null && $cekDelete
        ? array_push($respon['messages'], 'Pengeluaran Telah Diproses.') 
        : array_push($respon['messages'], 'Kesalahan');
      
      return $respon;
    }
  
  }