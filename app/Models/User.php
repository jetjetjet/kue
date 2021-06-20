<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;


use App\Libs\HasPermissionsTrait;

class User extends Authenticatable
{
    public $timestamps = false;
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username',
        'userfullname',
        'userpassword',
        'usercontact',
        'useraddress',
        'userjoindate',
        'useractive',
        'usercreatedat',
        'usercreatedby',
        'usermodifiedat',
        'usermodifiedby'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password'
    ];

    public function getAuthIdentifier()
    {       
        return $this->attributes['id'];
    }

    public function getUserName()
    {       
        return $this->attributes['username'];
    }

    public function getFullName()
    {       
        return $this->attributes['userfullname'];
    }

    public function permissions()
    {       
        return $this->attributes['permissions'];
    }

    public function getAuthPassword()
    {
        return $this->attributes['userpassword'];
    }

    public function getUserAttributes()
    {
        return $this->attributes;
    }

    public function can($actions, $args = array())
    {
        if($this->attributes['id'] == 1){
            $valids = [true];
        } else {
            $valids = array_unique(array_map(function ($action){
                return in_array($action, $this->attributes['permissions'], true);
            }, $actions));
        }
        return !in_array(false, $valids, true);
    }
}
