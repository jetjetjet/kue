<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MenuCategory extends Model
{
	use HasFactory;
	protected $table = 'menucategory';
  public $timestamps = false;
	protected $fillable = [
    'mcname',
    'mcactive',
    'mccreatedat',
    'mccreatedby',
    'mcmodifiedat',
    'mcmodifiedby'
  ];
}
