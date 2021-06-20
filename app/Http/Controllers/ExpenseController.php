<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use Illuminate\Http\Request;

use Validator;

use App\Libs\Helpers;
use App\Repositories\ExpenseRepository;
use App\Repositories\AuditTrailRepository;
use Auth;
class ExpenseController extends Controller
{
	public function index()
	{
		return view('Expense.index');
	}

	public function getLists(Request $request)
	{
		$permission = Array(
			'save' => (Auth::user()->can(['pengeluaran_simpan']) == true ? "true" : "false") . " as can_save",
			'delete' => (Auth::user()->can(['pengeluaran_hapus']) == true ? "true" : "false") . " as can_delete"
		);
		$results = ExpenseRepository::grid($permission);
		
		return response()->json($results);
	}

	public function getById(Request $request, $id = null)
	{
		$respon = Helpers::$responses;
		$results = ExpenseRepository::get($respon, $id);
        // dd($results);
		if($results['status'] == 'error'){
			$request->session()->flash($results['status'], $results['messages']);
			return redirect()->action([ExpenseController::class, 'index']);
		}

		return view('Expense.edit')->with('data', $results['data']);
	}

	public function save(Request $request)
	{
		$respon = Helpers::$responses;
		
		$rules = array(
			'expensename' => 'required',
			'expensedetail' => 'required'
		);

		$inputs = $request->all();
		$validator = validator::make($inputs, $rules);
// dd($inputs);
		if ($validator->fails()){
			return redirect()->back()->withErrors($validator)->withInput($inputs);
		}

		$loginid = Auth::user()->getAuthIdentifier();
		$results = ExpenseRepository::save($respon, $inputs, $loginid);
		AuditTrailRepository::saveAuditTrail($request->path(), $results, 'Simpan Pengeluaran', $loginid);
		//cek
		$request->session()->flash($results['status'], $results['messages']);
		return redirect()->action([ExpenseController::class, 'getById'], ['id' => $results['id']]);
	}

	public function deleteById(Request $request, $id)
	{
		$respon = Helpers::$responses;

		$loginid = Auth::user()->getAuthIdentifier();
		$results = ExpenseRepository::delete($respon, $id, $loginid);
		AuditTrailRepository::saveAuditTrail($request->path(), $results, 'Hapus Pengeluaran', $loginid);
		return response()->json($results);
	}

    public function proceedById(Request $request, $id)
	{
		$respon = Helpers::$responses;

		$loginid = Auth::user()->getAuthIdentifier();
		$results = ExpenseRepository::proceed($respon, $id, $loginid);
		AuditTrailRepository::saveAuditTrail($request->path(), $results, 'Proses Pengeluaran', $loginid);
        
		$request->session()->flash($results['status'], $results['messages']);
		$cekRes = $results['status'];
		if ($cekRes == 'success'){
			return redirect('/');
		}elseif($cekRes == 'error'){
			return redirect()->action([ExpenseController::class, 'getById'], ['id' => $results['id']]);
		}
	}
}
