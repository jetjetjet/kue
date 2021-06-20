<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;

use App\Libs\Helpers;
use App\Repositories\RoleRepository;
use App\Repositories\UserRepository;
use App\Repositories\AuditTrailRepository;
use Auth;

class RoleController extends Controller
{
  public function index()
	{
		return view('Role.index');
	}

	public function getLists(Request $request)
	{
		$perms = Array(
			'save' => (Auth::user()->can(['jabatan_simpan']) == true ? "true" : "false") . " as can_save",
			'delete' => (Auth::user()->can(['jabatan_hapus']) == true ? "true" : "false") . " as can_delete"
		);
		$results = RoleRepository::grid($perms);
		
		return response()->json($results);
	}

	public function getById(Request $request, $id = null)
	{
		$respon = Helpers::$responses;
		$results = RoleRepository::get($respon, $id);

		if($results['status'] == 'error'){
			$request->session()->flash($results['status'], $results['messages']);
			return redirect()->action([RoleController::class, 'index']);
    }
    $user = UserRepository::userActive()->select('id', 'username')->get();
		return view('Role.edit')->with('user', $user)->with('data', $results['data']);
	}

	public function save(Request $request)
	{
		$respon = Helpers::$responses;
		
		$rules = array(
			'rolename' => 'required'
		);

    $inputs = $request->all();
		$validator = validator::make($inputs, $rules);
		if ($validator->fails()){
			return redirect()->back()->withErrors($validator)->withInput($inputs);
		}

		$loginid = Auth::user()->getAuthIdentifier();
    $results = RoleRepository::save($respon, $inputs, $loginid);
		AuditTrailRepository::saveAuditTrail($request->path(), $results, 'Simpan Jabatan', $loginid);
		//cek
		$request->session()->flash($results['status'], $results['messages']);
		return redirect()->action([RoleController::class, 'getById'], ['id' => $results['roleid']]);
	}

	public function deleteById(Request $request, $id)
	{
		$respon = Helpers::$responses;

		$loginid = Auth::user()->getAuthIdentifier();
		$results = RoleRepository::delete($respon, $id, $loginid);
		AuditTrailRepository::saveAuditTrail($request->path(), $results, 'Hapus Jabatan', $loginid);
		return response()->json($results);
	}
}
