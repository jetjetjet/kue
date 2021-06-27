<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;

use App\Libs\Helpers;
use App\Repositories\ProductRepository;
use App\Repositories\AuditTrailRepository;
use Auth;

class ProductController extends Controller
{
  public function index()
  {
    return view('product.index');
  }

  public function getGrid()
  {
    $permission = Array(
			'save' => (Auth::user()->can(['product_simpan']) == true ? 1 : 0)."as can_save",
			'delete' => (Auth::user()->can(['product_hapus']) == true ? 1 : 0)."as can_delete"
		);
		$results = ProductRepository::grid($permission);
		
		return response()->json($results);
  }

  public function getById(Request $request, $id = null)
	{
		$respon = Helpers::$responses;
		$results = ProductRepository::get($respon, $id);

		if($results['status'] == 'error'){
			$request->session()->flash($results['status'], $results['messages']);
			return redirect()->action([ProductController::class, 'index']);
		}

		return view('Product.edit')->with('data', $results['data']);
	}

	public function apiGetDetail(Request $request, $id)
	{
		$respon = Helpers::$responses;
		$results = ProductRepository::apiDetail($respon, $id);
		
		return response()->json($results);
	}

	public function apiGetShowcaseCode($id)
	{
		$respon = Helpers::$responses;
		$results = ProductRepository::apiShowcaseCode($respon, $id);
		
		return response()->json($results);
	}

	public function save(Request $request)
	{
		$respon = Helpers::$responses;
		$rules = array(
			'productname' => 'required',
			'productpcid' => 'required',
			'productprice' => 'required'
		);
		
		$inputs = $request->all();
    $file = Helpers::prepareFile($inputs, '/doc/images/');
		$validator = validator::make($inputs, $rules);

		if ($validator->fails()){
			return redirect()->back()->withErrors($validator)->withInput($inputs);
		}
		$loginid = Auth::user()->getAuthIdentifier();
		$results = ProductRepository::save($respon, $inputs, $file, $loginid);
		AuditTrailRepository::saveAuditTrail($request->path(), $results, 'Simpan Pengeluaran', $loginid);
		//cek
		$request->session()->flash($results['status'], $results['messages']);
		if($results['status'] == "error"){
			return redirect()->back()->withInput($inputs);
		}

		return redirect()->action([ProductController::class, 'getById'], ['id' => $results['id']]);
	}

	public function deleteById(Request $request, $id)
	{
		$respon = Helpers::$responses;

		$loginid = Auth::user()->getAuthIdentifier();
		$results = ProductRepository::delete($respon, $id, $loginid);
		AuditTrailRepository::saveAuditTrail($request->path(), $results, 'Hapus Product', $loginid);

		return response()->json($results);
	}

	public function searchProductShowcase(Request $request)
  {
		$cari = $request->has('q') ? $request->q : null;
		$data = ProductRepository::searchProductShowcase($cari);
		
		return response()->json($data);
  }

  public function searchProducts(Request $request)
  {
		$cari = $request->has('q') ? $request->q : null;
		$data = ProductRepository::search($cari);
		
		return response()->json($data);
  }
}
