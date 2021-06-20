<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
	use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

	protected function mapRowsX($rows)
	{
		$items = array();
		if (!is_array($rows) || empty($rows)) return $items;

		$first = reset($rows);
		if (is_scalar($first)){
			return $rows;
		}

		// Loops row.
		$index = 1;
		foreach ($rows as $row){
			$item = (object)$row;
			$item->index = $index++;

			foreach ($item as $key => $value){
				if (!is_array($value)) continue;

				// Loops revursively.
				$item->{$key} = self::mapRowsX($value);
			}
			
			array_push($items, $item);
		}

		return $items;
	}
}
