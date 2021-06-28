<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

use Validator;

use App\Libs\Helpers;
use App\Repositories\ReportRepository;
use Auth;
class ReportController extends Controller
{
	public function index(Request $request)
	{
		$inputs = $request->all();
		$data = new \stdClass;
		$user = ReportRepository::getName();

		if(isset($inputs['startdate'])){
			$explode = explode(" to ", $inputs['startdate']);
			$inputs['startdate'] = $explode[0];
			$inputs['enddate'] = $explode[1];
			$data = ReportRepository::grid($inputs);
			// $data->sub = ReportRepository::get($inputs);
			// $data->sub = [];
			
		}else{
			$data->sub['total'] = '0';
		}
		// dd($data);
		return view('Report.index')->with('data', $data)->with('user', $user);
	}

	public function menuReport(Request $request)
	{
		$inputs = $request->all();
		// dd($inputs);
		$data = new \stdClass;
		if($inputs){
			$data = ReportRepository::getMenuReport($inputs);
		}
		// dd($data);
		return view('Report.menuRep')->with('data', $data);
	}
}