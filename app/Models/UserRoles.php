<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserRoles extends Model
{
    use HasFactory;
    protected $table = 'userroles';
    public $timestamps = false;
    protected $fillable = ['uruserid'
        ,'urroleid'
        ,'uractive'
        ,'urcreatedat'
        ,'urcreatedby'
        ,'urmodifiedat'
        ,'urmodifiedby'
        ,'rolemodifiedby'
    ];
}