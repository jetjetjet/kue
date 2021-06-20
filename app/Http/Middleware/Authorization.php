<?php

namespace App\Http\Middleware;

use Auth;
use Closure;
use App\Libs\AuthCafe;

class Authorization
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    private static $unauhorizedMessage = "Tidak dapat menjalankan perintah!";
    private static $unauthenticatedMessage = "Anda belum login!";
    
    public function handle($request, Closure $next, ...$actions)
    {
      $user = Auth::user();
      
      if (empty($user)){
        return self::terminateRequest($request, self::$unauthenticatedMessage, 403);
      }

      if (!empty($actions)){
        if (!AuthCafe::can($actions)){
          return self::terminateRequest($request, self::$unauhorizedMessage, 401);
        }
      }
      
      return $next($request);
    }
    
    private static function terminateRequest($request, $message, $code)
    {
      if ($request->ajax()){
        return response()->json([$message], $code);
      }

      $request->session()->flash('error', [$message]);
      return redirect('/');
    }
}
