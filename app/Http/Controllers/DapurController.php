<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;

use App\Libs\Helpers;
use App\Repositories\OrderRepository;
use App\Repositories\SettingRepository;
use Auth;

use App\Models\Order;
use App\Models\OrderDetail;
use DB;

class DapurController extends Controller
{
  public function index()
  {
    $ipserver = SettingRepository::getAppSetting('IPServer') ?? '127.0.0.1';
    return view('Dapur.index')->with('ipserver', $ipserver);
  }

  public function getLists(Request $request)
  {
    $data = OrderRepository::getDataDapur();

    return response()->json($data);
  }
}
