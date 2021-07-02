<?php
namespace App\Libs;

use Carbon\Carbon;
use Image;
use File;

class Helpers
{
  private static $permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
  
  public static $responses = array( 'state_code' => '', 'status' => '', 'messages' => array(), 'data' => Array());

  public static function prepareFile($inputs, $subFolder)
  {
    $file = new \StdClass;
    try {
      $file = isset($inputs['file']) ? $inputs['file'] : null;
      if($file != null){
        $file->path = storage_path('app/public/') . $subFolder;
        $file->newName = time()."_".$file->getClientOriginalName();
        $file->originalName = explode('.',$file->getClientOriginalName())[0];
        // $file->move($file->path ,$file->newName);
        Image::make($file)->save($file->path . '/' . $file->newName);

        //buat folder tumbnail
        $tumbPath = $file->path . 'thumbnail/';
        if (!File::isDirectory($tumbPath)) {
          File::makeDirectory($tumbPath);
        }

        $img = Image::make($file);
        $img->resize(160, 160)->save($tumbPath . $file->newName);
      }
    } catch (Exception $e){
        // supress
    }
    return $file;
  }

  public static function changeEnvironmentVariable($key,$value)
  {
    $path =  base_path('.env');
    if(is_bool(env($key)))
    {
      $old = env($key)? 'true' : 'false';
    }

    if (file_exists($path)) {
      file_put_contents($path, str_replace(
        "$key=".$old, "$key=".$value, file_get_contents($path)
      ));
    }
  }

  public static function generate_string($strength = 16) {
    $chars = self::$permitted_chars;
    $input_length = strlen($chars);
    $random_string = '';
    for($i = 0; $i < $strength; $i++) {
        $random_character = $chars[mt_rand(0, $input_length - 1)];
        $random_string .= $random_character;
    }
 
    return $random_string;
  }

  public static function getFilter($request)
  {
    $filter = new \stdClass();
    
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else {
        $ip = $_SERVER['REMOTE_ADDR'];
    }

    $filter->ip = $ip;

    // Custom filter.
    $filter->filter = (object)$request->input('filter');

    // Filter Date
    $tempDate = new \StdClass;
    if($request->input('filterDate')){
      $filterDate = explode(" to ",$request->input('filterDate'));
      $tempDate->from = $filterDate[0];
      $tempDate->to = $filterDate[1];
    // } else if($request->input('filterText') && $request->input('filterColumn')){
    //   $tempDate = null;
    } else {
      $tempDate->from = Carbon::now()->subDays(7)->format('d-m-Y');
      $tempDate->to = Carbon::now()->format('d-m-Y');
    }
    $filter->filterDate = $tempDate;
    
    // Global filter.
    // $filter->filterTexts = preg_split('/(-|\/)/', $request->input('search')['value']);

    // Columns.
    $columns = $request->input('columns') == null ? array() : $request->input('columns');
    
    // Filter columns.
    $filter->filterColumn = $request->input('filterColumn') ?? null;
    $filter->filterText = $request->input('filterText') ?? null;
    $filter->filterStatus = $request->input('filterStatus') ?? null;
    
    // Sort columns.
    $filter->sortColumns = array();
    $orderColumns = $request->input('order') != null ? $request->input('order') : array();
    foreach ($orderColumns as $value){
      $sortColumn = new \stdClass();
      $sortColumn->field = $columns[$value['column']]['data'];
      if (empty($sortColumn->field)) continue;
      
      $sortColumn->dir = $value['dir'];
      array_push($filter->sortColumns, $sortColumn);
    }
    
    // Paging.
    $filter->pageLimit = $request->input('length') ?: 1;
    $filter->pageOffset = $request->input('start') ?: 0;
    // Log::info(json_encode($filter));
    return $filter;
  }
}