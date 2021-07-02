<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

use Validator;

use App\Libs\Helpers;
use App\Repositories\ReportRepository;
use Auth;
use PDF;

class ReportController extends Controller
{
	public function index(Request $request)
	{
		$inputs = $request->all();
		$data = new \stdClass;

		if(isset($inputs['startdate'])){
			$explode = explode(" to ", $inputs['startdate']);
			$request['enddate'] = $explode[1] ?? $explode[0];
			
			$inputs['startdate'] = $explode[0] . ' 00:00:01';
			$inputs['enddate'] = ($explode[1] ?? $explode[0]) . ' 23:59:59';
			$inputs['expense'] = $inputs['expense'] ?? 1;
			
			$data = ReportRepository::grid($inputs);
			$data->label = \sprintf('Laporan Transaksi Periode %s - %s', $explode[0], $request['enddate']);
			// $data->sum = ReportRepository::sumTrx($inputs)[0];
			$print = $request->input('print');
			if(!empty($print)){
				$pdf = \App::make('dompdf.wrapper');
				/* Careful: use "enable_php" option only with local html & script tags you control.
				used with remote html or scripts is a major security problem (remote php injection) */
				$pdf->getDomPDF()->set_option("enable_php", true);
				$pdf->loadview('Report.trx_pdf', ['data' => $data]);
				return $pdf->stream('laporan-transaksi-periode-'.$inputs['startdate'].'-'.$inputs['enddate'].'.pdf');
			}
		}else{
			$data = [];
		}
		return view('Report.index')->with('data', $data);
	}

	public function productReport(Request $request)
	{
		$inputs = $request->all();
		$data = new \stdClass;
		if($inputs){
			$data = ReportRepository::getProductReport($inputs);
		}
		return view('Report.productRep')->with('data', $data);
	}
}