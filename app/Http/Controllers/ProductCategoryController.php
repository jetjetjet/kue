<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\ProductCategoryRepository;
use App\Repositories\AuditTrailRepository;
use App\Libs\Helpers;
use Auth;

class ProductCategoryController extends Controller
{
  public function save(Request $request)
	{
		$respon = Helpers::$responses;
		$inputs = $request->all();

		$loginid = Auth::user()->getAuthIdentifier();
		$results = ProductCategoryRepository::save($respon, $inputs, $loginid);
		AuditTrailRepository::saveAuditTrail($request->path(), $results, 'Simpan Product Kategori', $loginid);
		
		return response()->json($results, $results['state_code']);
	}

	public function delete(Request $request, $id)
	{
		$respon = Helpers::$responses;
		$loginid = Auth::user()->getAuthIdentifier();
		$results = ProductCategoryRepository::delete($respon, $id, $loginid);
		AuditTrailRepository::saveAuditTrail($request->path(), $results, 'Hapus Product Kategori', $loginid);
		
		return response()->json($results, $results['state_code']);
	}

	public function search(Request $request)
  {
		$cari = $request->has('q') ? $request->q : null;
		$data = ProductCategoryRepository::search($cari);
		
		return response()->json($data);
  }
}
