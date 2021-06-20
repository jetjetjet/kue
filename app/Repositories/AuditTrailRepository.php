<?php
namespace App\Repositories;

use App\Models\AuditTrail;
use DB;

class AuditTrailRepository
{
  public static function get($filter)
  {
    $q = Audittrail::join('users as u', 'u.id', 'createdby')
      ->select(
        'u.username',
        'path',
        'action',
        'status',
        'messages',
        'createdat');
    //Filter Tgl
    
    $count = $q->count();
    $q->whereRaw("createdat::date between '". $filter->filterDate->from . "'::date and '" . $filter->filterDate->to . "'::date");
    
    //Filter Kolom.
    if (!empty($filter->filterText) && !empty($filter->filterColumn)){
      // if (empty($filterText)) continue;
      $trimmedText = trim($filter->filterText);
      $text = strtolower(implode('%', explode(' ', $trimmedText)));
      $q->whereRaw('upper('.$filter->filterColumn .') like upper(?)', [ '%' . $text . '%']);
    }

    $countFiltered = $q->count();
    // Order.
    if (!empty($filter->sortColumns)){
      foreach ($filter->sortColumns as $value){
        $field = $value->field;
        if (empty($field)) continue;
        $q->orderBy($field, $value->dir);
      }
    } else {
      $q->orderBy('createdat', 'DESC');
    }

    // Paging.
    $q->skip($filter->pageOffset)
      ->take($filter->pageLimit);

    $grid = new \stdClass();
    $grid->recordsTotal = $count;
    $grid->recordsFiltered = $countFiltered;
    $grid->data = $q->get();

    return $grid;
  }

  public static function saveAuditTrail($path, $result, $action, $loginid)
  {
    try{
     $aut = AuditTrail::create([
        'path' => $path,
        'action' => $action,
        'status' => $result['status'],
        'messages' => $result['messages'][0] ?? null,
        'createdby' => $loginid,
        'createdat' => DB::raw("now()")
      ]);
    } catch(Exception $e)
    {
      //lewaaat
    }
  }
}