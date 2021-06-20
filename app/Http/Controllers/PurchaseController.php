<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use Illuminate\Support\Facades\Hash;

use App\Libs\Helpers;
use App\Libs\Cetak;
use App\Http\Controllers\ShiftController;
use App\Repositories\OrderRepository;
use App\Repositories\ProductRepository;
use App\Repositories\PurchaseRepository;
use App\Repositories\AuditTrailRepository;
use App\Repositories\SettingRepository;
// use App\Events\OrderProceed;
// use App\Events\BoardEvent;
use Auth;

class PurchaseController extends Controller
{
  public function index()
  {

  }

  public function edit(Request $request, $id = null)
  {
    $respon = Helpers::$responses;
		$products = json_encode(ProductRepository::getProductPurchase());
    $data = PurchaseRepository::getPurchase($respon, $id);

		if($data['status'] == 'error'){
			$request->session()->flash($data['status'], $data['messages']);
			return redirect()->action([OrderController::class, 'index']);
		}

    return view('Purchase.purchase')
			->with('products', $products)
			->with('data', $data['data']);
  }


  
}
