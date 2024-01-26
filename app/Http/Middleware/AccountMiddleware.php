<?php

namespace App\Http\Middleware;

use Closure;
use App\Utils\Roles;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;

class AccountMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        // $user = Auth::user();
        // $role = $user->role;

        // if (
        //     $role == Roles::DEVELOPER ||
        //     $role == Roles::HIS_AND_DIGITAL_LITERACY_MANAGER ||
        //     $role == Roles::MEAL_AND_DIGITAL_TOOL_OFFICER ||
        //     $role == Roles::MEAL_ASSOCIATE
        // ) {
            return $next($request);
      //  }

        return jsend_fail(['message' => "You don't have permission."], JsonResponse::HTTP_UNAUTHORIZED);
    }
}
