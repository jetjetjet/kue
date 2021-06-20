<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    use hasFactory;
    public $timestamps = false;
      protected $fillable = [
      'expensename',
      'expensedetail',
      'expenseprice',
      'expensedate',      
      'expenseactive',
      'expenseexecutedat',
      'expenseexecutedby',
      'expensecreatedat',
      'expensecreatedby',
      'expensemodifiedat',
      'expensemodifiedby'
    ];
  
    public static function getFields($model){
      $model->id = null;
      $model->expensename = null;
      $model->expensedetail = null;
      $model->expenseprice = null;
      $model->expensedate = null;
      $model->expenseexecutedby = null;
      $model->expenseexecutedby = null;
  
      return $model;
    }
}
