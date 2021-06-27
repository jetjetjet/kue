<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\OrderRepository;
use App\Repositories\ProductRepository;
use Carbon\Carbon;
use Auth;

class DashboardController extends Controller
{
	public function index(Request $request)
  {
    $data = new \StdClass();
    $data->thn = Array();
    $data->count = OrderRepository::dashboardCount();
    $data->PO = OrderRepository::dashboardPO();
    $thn = Carbon::now()->format('Y');
    $crThn = 2021 - $thn;
    array_push($data->thn,$thn-0);
    for($i=0; $i<$crThn; $i++){
      $thn = $thn - 1;
      array_push($data->thn,$thn);
    }
    
    $blnNow = Carbon::now()->format('m');
    $data->bln = Array();
    $bln = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
    foreach($bln as $key  => $val){
      $anka = $key + 1;
      $temp = new \StdClass();
      $temp->bln = $val;
      $temp->val = strlen($anka) == 1 ? 0 . $anka:$key;;
      $temp->skrg = $blnNow == $key + 1 ? true:false;
      array_push($data->bln, $temp);
    }
    
    return view('Dashboard.index')->with('data', $data);
  }

  public function getChart(Request $request)
  {
    $data = Array();
    $bln = isset($request->bulan) ? $request->bulan : Carbon::now()->format('m');
    $thn = isset($request->tahun) ? $request->tahun : Carbon::now()->format('Y');
    $filter = $thn . '-' . $bln;
    $parse = Carbon::parse($filter); 
    $data['blnAktif'] = $parse->isoFormat('MMMM');

    $awal = $parse->startOfMonth()->toDateString();
    $akhir = $parse->endOfMonth()->toDateString();
    $filter = Array('awal' => $awal, 'akhir' => $akhir);
    $range = range($parse->startOfMonth()->format('d'), $parse->endOfMonth()->format('d'));
    $yM = $parse->endOfMonth()->format('Y-m');
    $data['chart'] = OrderRepository::orderChart($filter, $range, $yM );

    //Menu
    $filterMenu = Array( 
      1 => "odcreatedat::date between '". $awal . "'::date and '" . $akhir . "'::date"
    );
    $data['topMenu'] = ProductRepository::topProduct($filterMenu);

    return response()->json($data);
  }
}
