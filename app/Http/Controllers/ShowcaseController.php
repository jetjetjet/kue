<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;

use App\Libs\Helpers;
use App\Repositories\ShowcaseRepository;
use App\Repositories\AuditTrailRepository;
use Auth;

class ShowcaseController extends Controller
{
  public function index()
	{
		return view('showcase.index');
	}

	public function getLists(Request $request)
	{
		$filter = Helpers::getFilter($request);
		$permission = Array(
			'save' => (Auth::user()->can(['showcase_simpan']) == true ? 1 : 0)."as can_save",
			'delete' => (Auth::user()->can(['showcase_hapus']) == true ? 1 : 0)."as can_delete"
		);
		$results = ShowcaseRepository::grid($filter, $permission);
		
		return response()->json($results);
	}

	public function getById(Request $request, $id = null)
	{
		$respon = Helpers::$responses;
		$results = ShowcaseRepository::get($respon, $id);

		if($results['status'] == 'error'){
			$request->session()->flash($results['status'], $results['messages']);
			return redirect()->action([ShowcaseController::class, 'index']);
		}

		return view('Showcase.edit')->with('data', $results['data']);
	}

	public function save(Request $request)
	{
		$respon = Helpers::$responses;
		$rules = array(
			'showcaseproductid' => 'required',
			'showcaseqty' => 'required',
			'showcasedate' => 'required'
		);
    
		$inputs = $request->all();
		$validator = validator::make($inputs, $rules);

		if ($validator->fails()){
			return redirect()->back()->withErrors($validator)->withInput($inputs);
		}

		if(!$inputs['id']){
			$inputs['randomstr'] = Helpers::generate_string(5);
		}
// dd($inputs);
		$loginid = Auth::user()->getAuthIdentifier();
		$results = ShowcaseRepository::save($respon, $inputs, $loginid);
		AuditTrailRepository::saveAuditTrail($request->path(), $results, 'Simpan Pengeluaran', $loginid);
		//cek
		$request->session()->flash($results['status'], $results['messages']);
		if($results['status'] == "error"){
			return redirect()->back()->withInput($inputs);
		}

		return redirect()->action([ShowcaseController::class, 'getById'], ['id' => $results['id']]);
	}

	public function deleteById(Request $request, $id)
	{
		$respon = Helpers::$responses;

		$loginid = Auth::user()->getAuthIdentifier();
		$results = ShowcaseRepository::delete($respon, $id, $loginid);
		AuditTrailRepository::saveAuditTrail($request->path(), $results, 'Hapus Showcase', $loginid);

		return response()->json($results);
	}

  public function expiredById(Request $request, $id)
	{
		$respon = Helpers::$responses;
    $inputs = $request->all();
    
		$loginid = Auth::user()->getAuthIdentifier();
		$results = ShowcaseRepository::expired($respon, $id, $loginid, $inputs);
		AuditTrailRepository::saveAuditTrail($request->path(), $results, 'Showcase expired', $loginid);

		return response()->json($results);
	}
  public function updateById(Request $request, $id)
	{
		$respon = Helpers::$responses;
    $inputs = $request->all();

		$loginid = Auth::user()->getAuthIdentifier();
		$results = ShowcaseRepository::updateStock($respon, $id, $inputs, $loginid);
		AuditTrailRepository::saveAuditTrail($request->path(), $results, 'Update Stock Showcase', $loginid);

		return response()->json($results);
	}
}
