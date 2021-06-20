<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubPromo extends Model
{
	use HasFactory;

	protected $table = 'subpromo';
  public $timestamps = false;
	protected $fillable = [
    'sppromoid',
    'spmenuid',
		'spindex',
    'spactive',
    'spcreatedat',
    'spcreatedby',
    'spmodifiedby'
  ];
}