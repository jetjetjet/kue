<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;

use App\Libs\Helpers;
use App\Repositories\MenuRepository;
use App\Repositories\AuditTrailRepository;
use Auth;
use Illuminate\Support\Facades\File;
use Image;

class MenuController extends Controller
{
	public function index()
	{
		return view('Menu.index');
	}

	public function getLists(Request $request)
	{
		$permission = Array(
			'save' => (Auth::user()->can(['menu_simpan']) == true ? "true" : "false") . " as can_save",
			'delete' => (Auth::user()->can(['menu_hapus']) == true ? "true" : "false") . " as can_delete"
		);
		$results = MenuRepository::grid($permission);
		
		return response()->json($results);
	}

	public function getById(Request $request, $id = null)
	{
		$respon = Helpers::$responses;
		$results = MenuRepository::get($respon, $id);

		if($results['status'] == 'error'){
			$request->session()->flash($results['status'], $results['messages']);
			return redirect()->action([MenuController::class, 'index']);
		}

		return view('Menu.edit')->with('data', $results['data']);
	}

	public function save(Request $request)
	{
		$respon = Helpers::$responses;
		$rules = array(
			'menuname' => 'required',
			'menumcid' => 'required',
			'menuprice' => 'required|integer'
		);

		$inputs = $request->all();
		$validator = validator::make($inputs, $rules);

		if ($validator->fails()){
			return redirect()->back()->withErrors($validator)->withInput($inputs);
		}
	
		if($request['id'] == null){
			$idimg = $request['getid'];
		}else{
		$idimg = $request['id'];
		}
		if($request['menuimg'] == !null){
			$file = $request['menuimg'];
			$imageName = time()."_".$file->getClientOriginalName();
			$path = '/app/public/images/';
			
			if(!File::exists(storage_path().$path)) {
				File::makeDirectory(storage_path().$path, 0755, true, true);
			}

			$img = Image::make($file->getRealPath());
			$img->resize(400, 400, function ($constraint) {
				$constraint->aspectRatio();
			})->save(storage_path().$path.$imageName);

		  $inputs['menuimgpath'] = '/storage/images/' . $imageName;
		} elseif($request['delimg'] == '1'){
			$inputs['menuimgpath'] = null ;
			$delimgpath = storage_path().'/app/public/images/'.$request['hidimg'];
			if(File::exists($delimgpath)) {
				File::delete($delimgpath);
			}
		} elseif($request['menuimg'] == null){
			$inputs['menuimgpath'] = $request['hidimg'];
		}

		$loginid = Auth::user()->getAuthIdentifier();
		$results = MenuRepository::save($respon, $inputs, $loginid);
		AuditTrailRepository::saveAuditTrail($request->path(), $results, 'Simpan Menu', $loginid);
		//cek
		$request->session()->flash($results['status'], $results['messages']);
		return redirect()->action([MenuController::class, 'getById'], ['id' => $results['id']]);
	}

	public function deleteById(Request $request, $id)
	{
		$respon = Helpers::$responses;

		$loginid = Auth::user()->getAuthIdentifier();
		$results = MenuRepository::delete($respon, $id, $loginid);
		AuditTrailRepository::saveAuditTrail($request->path(), $results, 'Hapus Menu', $loginid);
		return response()->json($results);
	}

	public function menuOrder(Request $request)
	{
		$respon = Helpers::$responses;

		$results = MenuRepository::menuapi($respon);
		return response()->json($results);
	}

	public function searchMenu(Request $request)
  {
		$cari = $request->has('q') ? $request->q : null;
		$data = MenuRepository::search($cari);
		
		return response()->json($data);
  }
}
