<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AuditTrail extends Model
{
    use HasFactory;
    protected $table = 'audittrails';
    public $timestamps = false;
    protected $fillable = [
        'path',
        'action',
        'mode',
        'status',
        'messages',
        'createdat',
        'createdby'
    ];
}
