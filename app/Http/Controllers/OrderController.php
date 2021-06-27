<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use Illuminate\Support\Facades\Hash;

use App\Libs\Helpers;
use App\Libs\Cetak;
// use App\Http\Controllers\ShiftController;
use App\Repositories\OrderRepository;
use App\Repositories\MenuRepository;
use App\Repositories\ProductRepository;
use App\Repositories\AuditTrailRepository;
use App\Repositories\ShiftRepository;
use App\Repositories\SettingRepository;
use Auth;
use Yajra\DataTables\Facades\DataTables;

class OrderController extends Controller
{
  public function index()
	{
		return view('Order.index');
	}

	public function grid(Request $request)
	{
		$filter = Helpers::getFilter($request);
		$permission = Array(
			'save' => (Auth::user()->can(['order_simpan']) == true ? 1 : 0),
			'delete' => (Auth::user()->can(['order_hapus']) == true ? 1 : 0)
		);
		$results = OrderRepository::grid($filter, $permission);
		
		return response()->json($results);
	}

  public function order(Request $request, $id = null)
  {
    $respon = Helpers::$responses;
		
		$products = ProductRepository::getProductOrder();
    $data = OrderRepository::getOrder($respon, $id);

		if($data['status'] == 'error'){
			$request->session()->flash($data['status'], $data['messages']);
			return redirect()->action([OrderController::class, 'orderView']);
		}

    return view('Order.pickMenu')
			->with('products', $products)
			->with('data', $data['data']);
  }

  public function detail(Request $request, $id)
  {
    $url = $request->path();
    $kasir = Auth::user()->can(['order_pembayaran'],[]);
    // if($kasir){
    //   $cekShift = ShiftRepository::cekShiftStatus();
    //   if (!$cekShift){
		// 		$request->session()->put('urlintend', (string)$url);
    //     $request->session()->flash('warning', ['Shift belum diisi. Mohon diisi terlebih dahulu']);
				
    //     return redirect()->action([ShiftController::class, 'getById']);
    //   }
    // }
    $respon = Helpers::$responses;
    $results = OrderRepository::getOrder($respon, $id);

		if($results['status'] == 'error'){
			$request->session()->flash($results['status'], $results['messages']);
			return redirect()->action([OrderController::class, 'index']);
		}

    return view('Order.detail')->with('data', $results['data']);
  }

  public function getDetail(Request $request, $idOrder)
	{
		$results = OrderRepository::GetSubOrder($idOrder);
		
		return response()->json($results);
	}

  public function save(Request $request, $id = null)
  {
    $respon = Helpers::$responses;
		$inputs = $request->all();

		// Subs.
		$inputs['dtl'] = $this->mapRowsX(isset($inputs['dtl']) ? $inputs['dtl'] : null);

		$loginid = Auth::user()->getAuthIdentifier();
    $results = OrderRepository::save($respon, $id, $inputs, $loginid);
		AuditTrailRepository::saveAuditTrail($request->path(), $results, 'Buat Pesanan', $loginid);

		if($results['status'] == 'error'){
			$request->session()->flash($results['status'], $results['messages']);
			return redirect()->back()->withInput($inputs);
		}

		return redirect()->action([OrderController::class, 'detail'], ['id' => $results['id']]);
  }

  public function deleteById(Request $request, $id)
	{
		$respon = Helpers::$responses;

		$loginid = Auth::user()->getAuthIdentifier();
		$results = OrderRepository::delete($respon, $id, $loginid);
		AuditTrailRepository::saveAuditTrail($request->path(), $results, 'Hapus Pesanan', $loginid);

		return response()->json($results);
	}

  public function voidById(Request $request, $id)
	{
		$respon = Helpers::$responses;

    $inputs = $request->all();

		$loginid = Auth::user()->getAuthIdentifier();
		$results = OrderRepository::void($respon, $id, $loginid, $inputs);
		AuditTrailRepository::saveAuditTrail($request->path(), $results, 'Batalkan Pesanan', $loginid);

		return response()->json($results);
	}
  
  public function paidById(Request $request, $id)
	{
		$respon = Helpers::$responses;

    $inputs = $request->all();
 
    $loginid = Auth::user()->getAuthIdentifier();
		$results = OrderRepository::paid($respon, $id, $loginid, $inputs);
		AuditTrailRepository::saveAuditTrail($request->path(), $results, "Transaksi", $loginid);

		// if($results['status'] == "success"){
		// 	event(new BoardEvent('ok'));
		// 	event(new OrderProceed('ok'));
		// }
		self::orderReceiptkasir($id, $request);
		$request->session()->flash($results['status'], $results['messages']);

		return redirect('/');
	}

  public function completeById(Request $request, $id)
	{
		$respon = Helpers::$responses;

    $inputs = $request->all();
 
    $loginid = Auth::user()->getAuthIdentifier();
		$results = OrderRepository::complete($respon, $id, $loginid);
		AuditTrailRepository::saveAuditTrail($request->path(), $results, "Transaksi", $loginid);

		// if($results['status'] == "success"){
		// 	event(new BoardEvent('ok'));
		// 	event(new OrderProceed('ok'));
		// }
		self::orderReceiptkasir($id, $request);
		$request->session()->flash($results['status'], $results['messages']);

		return redirect('/');
	}

	public function orderReceipt($idOrder)
	{
		$data = OrderRepository::getOrderReceipt($idOrder);
		$cetak = Cetak::print($data);
	}

	public function orderReceiptkasir($id, Request $request)
	{
		$data = OrderRepository::getOrderReceiptkasir($id);
		$inputs = $request->all();
		
		$cetak = Cetak::printkasir($data, $inputs);
		return redirect('/order/meja/view');
	}

	public function opendrawer(Request $request)
	{
		$respon = Helpers::$responses;
		$cetak = Cetak::bukaLaci($respon);

		return response()->json($cetak);
	}

	public function opendraweraudit(Request $request)
	{
		$respon = Helpers::$responses;
		$inputs = $request->all();
		$loginid = Auth::user()->getAuthIdentifier();
		$inputs['pass1'] = SettingRepository::getAppSetting('PasswordLaci');
	
		$cek = Hash::check($inputs['pass'], $inputs['pass1']);

		if($cek == false){
			array_push($respon['messages'], 'Periksa Kembali Password Anda');
			$respon['status'] = "error";
			$respon['errorMessages'] = "Salah Password";
			AuditTrailRepository::saveAuditTrail($request->path(), $respon, 'Buka Laci', $loginid);
			return response()->json($respon);
		}else{
			$cetak = Cetak::bukaLaci($respon);
			AuditTrailRepository::saveAuditTrail($request->path(), $cetak, 'Buka Laci', $loginid);
			return response()->json($cetak);
		}		
	}

	public function ping(Request $request)
	{
		$respon = Helpers::$responses;
		$cetak = Cetak::ping($respon);
		return response()->json($cetak);
	}
}