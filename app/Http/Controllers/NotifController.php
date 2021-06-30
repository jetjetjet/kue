<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\OrderRepository;
use App\Libs\Helpers;

class NotifController extends Controller
{
  public function notifIcon()
	{
		$respon = Helpers::$responses;
		$results = OrderRepository::notifCount($respon);
		
		return response()->json($results);
	}

	public function getNotif()
	{
		$respon = Helpers::$responses;
		$results = OrderRepository::notifTopbar($respon);
		
		return response()->json($results);
	}
}
