<?php

namespace App\Http\Middleware;

use App\Helpers\ResponseFormatter;
use Closure;
use Illuminate\Support\Facades\DB;

class CheckToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // cek token
        $checkToken = DB::table('admins')->where('remember_token', $request->_token)->where('is_active_token', 1)->count();
        if ($checkToken == 0) {
            
            return ResponseFormatter::error([
                'message' => 'Session tidak ada, login ulang!',
            ], 402);
        }

        return $next($request);
    }
}
