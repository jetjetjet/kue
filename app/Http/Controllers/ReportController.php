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
		$print = $request->input('print');
		$data = new \stdClass;

		if(isset($inputs['startdate'])){
			$explode = explode(" to ", $inputs['startdate']);
			$inputs['startdate'] = $explode[0];
			$inputs['enddate'] = $explode[1];
			$inputs['expense'] = $inputs['expense'] ?? 1;

			$data->grid = ReportRepository::grid($inputs);
			$data->sum = ReportRepository::sumTrx($inputs)[0];

			if(!empty($print)){
				// $pdf = PDF::loadview('Report.trx_pdf',['data'=>$data]);
				// $pdf->output();
				// $dom_pdf = $pdf->getDomPDF();

				// $canvas = $pdf->get_canvas();
				// $canvas->page_text(0, 0, "Page {PAGE_NUM} of {PAGE_COUNT}", null, 10, array(0, 0, 0));

				// return $dom_pdf->download();


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