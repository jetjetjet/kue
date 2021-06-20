<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;

use App\Libs\Helpers;
use App\Repositories\UserRepository;
use Auth;

class UserController extends Controller
{
  public function index()
	{
		return view('User.index');
	}

	public function getLists(Request $request)
	{
		$perms = Array(
			'save' => (Auth::user()->can(['user_simpan']) == true ? "true" : "false") . " as can_save",
			'delete' => (Auth::user()->can(['user_hapus']) == true ? "true" : "false") . " as can_delete"
		);
		$results = UserRepository::grid($perms);
		
		return response()->json($results);
	}

	public function getById(Request $request, $id = null)
	{
		$respon = Helpers::$responses;
		$results = UserRepository::get($respon, $id);

		if($results['status'] == 'error'){
			$request->session()->flash($results['status'], $results['messages']);
			return redirect()->action([UserController::class, 'index']);
		}

		return view('User.edit')->with('data', $results['data']);
	}

	public function getProfile(Request $request, $id)
	{
		$respon = Helpers::$responses;
		if($id == Auth::user()->getAuthIdentifier()){
			$results = UserRepository::get($respon, $id);
			return view('User.profile')->with('data', $results['data']);
		} else {
			$request->session()->flash('error', ['Tidak dapat menjalankan perintah!']);
			return redirect('/');
		}
	}

	public function saveProfile(Request $request, $id)
	{
		$respon = Helpers::$responses;
		if($id == Auth::user()->getAuthIdentifier()){
			$inputs = $request->all();
			$results = UserRepository::save($respon, $inputs, Auth::user()->getAuthIdentifier());
			$request->session()->flash($results['status'], $results['messages']);
			return redirect()->action([UserController::class, 'getProfile'], ['id' => $id]);
		} else {
			$request->session()->flash('error', ['Tidak dapat menjalankan perintah!']);
			return redirect('/');
		}
	}

	public function changeProfilePassword(Request $request, $id)
	{
		$respon = Helpers::$responses;
		if($id == Auth::user()->getAuthIdentifier()){
			$inputs = $request->all();
			$results = UserRepository::changepassword($respon, $id, $inputs, Auth::user()->getAuthIdentifier());
			return response()->json($results);
		} else {
			array_push($respon['messages'], 'Tidak dapat menjalankan perintah!');
			$respon['status'] = "error";
			return response()->json($respon);
		}
	}

	public function save(Request $request)
	{
		$respon = Helpers::$responses;
		$inputs = $request->all();
		$rules = array(
			'username' => 'required',
			'userfullname' => 'required'
		);
		
		if(!isset($inputs['id'])){
			$rules = array(
				'username' => 'required|unique:users,username',
				'userpassword' => 'required',
				'userfullname' => 'required'
			);
		}

		$validator = validator::make($inputs, $rules);

		if ($validator->fails()){
			return redirect()->back()->withErrors($validator)->withInput($inputs);
		}

		$results = UserRepository::save($respon, $inputs, Auth::user()->getAuthIdentifier());
		//cek
		$request->session()->flash($results['status'], $results['messages']);
		return redirect()->action([UserController::class, 'getById'], ['id' => $results['id']]);
	}

	public function changePassword(Request $request, $id)
	{
		$respon = Helpers::$responses;
		
		$rules = array(
			'userpassword' => 'required'
		);

		$inputs = $request->all();
		$validator = validator::make($inputs, $rules);
		if($validator->fails()){
			array_push($respon['messages'], 'Periksa Kembali Password Anda');
			$respon['status'] = "error";
			return response()->json($respon);
		}

		$results = UserRepository::changepassword($respon, $id, $inputs, Auth::user()->getAuthIdentifier());
		//cek
		return response()->json($results);
	}

	public function deleteById(Request $request, $id)
	{
		$respon = Helpers::$responses;

		$results = UserRepository::delete($respon, $id, Auth::user()->getAuthIdentifier());
		return response()->json($results);
	}

	public function searchUser(Request $request)
  {
    if ($request->has('q')) {
      $cari = $request->q;
      $data = UserRepository::search($cari);
      
      return response()->json($data);
    }
  }
}
