<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;

use Validator;
use App\Libs\KapeWs;
use Ratchet\App;
use Ratchet\Server\EchoServer;

use Illuminate\Support\Facades\Hash;
use App\Libs\Helpers;
use App\Repositories\SettingRepository;
use App\Repositories\AuditTrailRepository;
use Illuminate\Support\Facades\Artisan;
use Exception;

use Auth;
class SettingController extends Controller
{
	public function index()
	{
		return view('Setting.index');
	}

	public function indexSetup()
	{
		$bool = is_bool(env('APP_FRESH')) ? env('APP_FRESH') : false;
		if($bool){
			return view('Setting.settingCafe');
		} else {
			return redirect('/');
		}
	}

	public function getLists(Request $request)
	{
		$perms = Array(
			'save' => (Auth::user()->can(['pengaturan_simpan']) == true ? "true" : "false") . " as can_save"
		);
		$results = SettingRepository::grid($perms);
		
		return response()->json($results);
	}

	public function getById(Request $request, $id = null)
	{
		$respon = Helpers::$responses;
		$results = SettingRepository::get($respon, $id);

		if($results['status'] == 'error'){
			$request->session()->flash($results['status'], $results['messages']);
			return redirect()->action([SettingController::class, 'index']);
		}

		return view('Setting.edit')->with('data', $results['data']);
	}

	public function save(Request $request)
	{
		$respon = Helpers::$responses;
		
		$rules = array(
			'settingcategory' => 'required'
		);

		$inputs = $request->all();
		
		if($inputs['settingkey'] == 'PasswordLaci'){
			$inputs['settingvalue'] = Hash::make($inputs['settingvalue']);
		}

		if($inputs['settingkey'] == 'logoApp'){
			$path = '\public\images';
			$file = Helpers::prepareFile($inputs, $path);
			$inputs['settingvalue'] = '\images\\' . $file->newName;
		}
		// dd($inputs);
		$validator = validator::make($inputs, $rules);

		if ($validator->fails()){
			return redirect()->back()->withErrors($validator)->withInput($inputs);
		}

		$loginid = Auth::user()->getAuthIdentifier();
		$results = SettingRepository::save($respon, $inputs, $loginid);
		AuditTrailRepository::saveAuditTrail($request->path(), $results, 'Ubah Setting', $loginid);
		//cek
		$request->session()->flash($results['status'], $results['messages']);
		return redirect()->action([SettingController::class, 'getById'], ['id' => $results['id']]);
	}

	public function initAppSetup(Request $request)
	{
		$bool = is_bool(env('APP_FRESH')) ? env('APP_FRESH') : false;
		if(!$bool){
			return redirect('/');
		}
		
		try{
			$init = Artisan::call('migrate:fresh --seed');
			
		}catch(\Exception $err){
			$request->session()->flash('error', ['Tidak dapat membuat konfigurasi aplikasi. Hubungi Administrator Aplikasi!']);
			return view('Setting.initApp');
		}

		return redirect()->action([SettingController::class, 'indexSetup']);
	}

	public function postSettingSetup(Request $request)
	{
		$respon = Helpers::$responses;
		
		$rules = array(
			'NamaApp' => 'required',
			'Alamat' => 'required',
			'KodeInvoice' => 'required',
			'HeaderStruk' => 'required',
			'FooterStruk' => 'required', 
			'HeaderStrukKasir' => 'required',
			'FooterStrukKasir' => 'required', 
			'IpPrinter' => 'required',
			'PasswordLaci' => 'required',
			'Telp' => 'required'
		);

		$inputs = $request->all();
		$inputs['logoApp'] = Helpers::prepareFile($inputs, '\public\images');
		$inputs['PasswordLaci'] = Hash::make($inputs['PasswordLaci']);
		$validator = validator::make($inputs, $rules);

		if ($validator->fails()){
			return redirect()->back()->withErrors($validator)->withInput($inputs);
		}

		$results = SettingRepository::saveInitApp($respon, $inputs);
		if($results['status'] == 'success')
			$a = Helpers::changeEnvironmentVariable('APP_FRESH', 'false');

		return redirect('/');
	}

	public function backupDb(Request $request)
	{
		if($request->proses){
			$backup = Artisan::call('backup:run --only-db --disable-notifications');
				if($backup == "0")
					$request->session()->flash('success', ['OK']);
		}

		return view('Setting.backupdb');
	}

	public function aboutus()
	{
		return view('Setting.aboutUs');
	}

	public function hotkey()
	{
		return view('Setting.hotKeys');
	}

	public function initSocket()
	{
		return view('Setting.initSocket');
	}

	public function startSocket()
	{
		$server_ip = gethostbyname($_SERVER['SERVER_NAME']);
		$app = new App($server_ip, 8910, '0.0.0.0');
		$app->route('/kapews', new KapeWs, array('*'));
    $app->run();
	}
}
