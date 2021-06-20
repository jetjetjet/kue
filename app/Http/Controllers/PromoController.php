<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;

use App\Libs\Helpers;
use App\Repositories\PromoRepository;
use App\Repositories\AuditTrailRepository;
use Auth;

class PromoController extends Controller
{
  public function index()
	{
		return view('promo.index');
	}

	public function getLists(Request $request)
	{
		$permission = Array(
			'save' => (Auth::user()->can(['promo_simpan']) == true ? 1 : 0),
			'delete' => (Auth::user()->can(['promo_hapus']) == true ? 1 : 0)
		);
		$results = PromoRepository::grid($permission);
		
		return response()->json($results);
	}

	public function getById(Request $request, $id = null)
	{
		$respon = Helpers::$responses;
		$results = PromoRepository::get($respon, $id);

		if($results['status'] == 'error'){
			$request->session()->flash($results['status'], $results['messages']);
			return redirect()->action([PromoController::class, 'index']);
		}

		return view('Promo.edit')->with('data', $results['data']);
	}

	public function save(Request $request)
	{
		$respon = Helpers::$responses;
		$rules = array(
			'promoname' => 'required',
			'promostart' => 'required',
			'promoend' => 'required',
			'promodiscount' => 'required'
		);
		
		$inputs = $request->all();

		// Subs.
		$inputs['sub'] = $this->mapRowsX(isset($inputs['sub']) ? $inputs['sub'] : null);
		
		$validator = validator::make($inputs, $rules);

		if ($validator->fails()){
			return redirect()->back()->withErrors($validator)->withInput($inputs);
		}

		$loginid = Auth::user()->getAuthIdentifier();
		$results = PromoRepository::save($respon, $inputs, $loginid);
		AuditTrailRepository::saveAuditTrail($request->path(), $results, 'Simpan Pengeluaran', $loginid);
		//cek
		$request->session()->flash($results['status'], $results['messages']);
		if($results['status'] == "error"){
			return redirect()->back()->withInput($inputs);
		}

		return redirect()->action([PromoController::class, 'getById'], ['id' => $results['id']]);
	}

	public function deleteById(Request $request, $id)
	{
		$respon = Helpers::$responses;

		$loginid = Auth::user()->getAuthIdentifier();
		$results = PromoRepository::delete($respon, $id, $loginid);
		AuditTrailRepository::saveAuditTrail($request->path(), $results, 'Hapus Promo', $loginid);

		return response()->json($results);
	}

	public function deleteSub(Request $request, $idSub)
	{
		$respon = Helpers::$responses;

		$loginid = Auth::user()->getAuthIdentifier();
		$results = PromoRepository::deleteSub($respon, $idSub, $loginid);
		AuditTrailRepository::saveAuditTrail($request->path(), $results, 'Hapus Sub Promo', $loginid);

		return response()->json($results);
	}
}
