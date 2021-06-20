<?php

namespace App\Providers;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Support\ServiceProvider;

use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Repositories\AuditTrailRepository;

use DB;

class CustomUserProvider implements UserProvider
{
    public function retrieveById($identifier)
    {
        $user = User:: //select('id', 'user_password as password', 'user_name as username', 'user_full_name as fullname')->
            where('id', $identifier)
            ->where('useractive', '1')
            ->orderBy('usercreatedat')->first();

        $user->permissions = self::cek($user->id);
        return $user;
    }

    public function retrieveByToken($identifier, $token)
    {
    }
    
    public function updateRememberToken(Authenticatable $user, $token)
    {
    }

    public function retrieveByCredentials(array $credentials)
    {
        $result = [];
        $username =  $credentials['username'];
        $password =  $credentials['password'];
        $user = User:: //select('id', 'user_password as password', 'user_name as username', 'user_full_name as fullname')->
            where('username', $username)
            ->where('useractive', '1')
            ->orderBy('usercreatedat')->first();
        if ($user === null || !Hash::check($password, $user['userpassword'])){
            $result['status'] = 'error';
            $audit = AuditTrailRepository::saveAuditTrail('/login', $result, 'Login', $user->id ?? 0);
            return null;
        };
        $user->permissions = self::cek($user->id);
        $result['status'] = 'success';
        $audit = AuditTrailRepository::saveAuditTrail('/login', $result, 'Login', $user->id ?? 0);
        return $user;
    }

    public function validateCredentials(Authenticatable $user, array $credentials)
    {
        if ($user === null) return false;
        return Hash::check($credentials['password'], $user->getAuthPassword());
    }

    public static function cek($id)
    {
        $permissions = DB::table('users')
        ->join('userroles', 'users.id', 'uruserid')
        ->join('roles', 'roles.id','urroleid')
        ->where('useractive', '1')
        ->where('uractive', '1')
        ->where('roleactive', '1')
        ->where('users.id', $id) //Auth::user()->getAuthIdentifier())
        ->select('rolepermissions')
        ->get();
        $perm = array();
        foreach($permissions as $p){
            $ar = explode(',', $p->rolepermissions);
            foreach($ar as $r){
                array_push($perm, $r);
            }
        }
        $perm = array_unique($perm);

        return $perm;
    }
}
