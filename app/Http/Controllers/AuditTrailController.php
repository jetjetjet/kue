<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;

use App\Libs\Helpers;
use App\Repositories\AuditTrailRepository;
use Auth;

class AuditTrailController extends Controller
{
  public function index()
	{
		return view('AuditTrail.index');
	}

	public function grid(Request $request)
	{
		$filter = Helpers::getFilter($request);
		$data = AuditTrailRepository::get($filter);

		return response()->json($data);
	}
}
