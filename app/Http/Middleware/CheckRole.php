<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use ErrorException;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Lcobucci\JWT\Encoding\JoseEncoder;
use Lcobucci\JWT\Token\Parser;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
//        dd($request);
        try {
            $Token = $request->bearerToken();
            $tokenId = (new Parser(new JoseEncoder()))->parse($Token)->claims()->all();
            $user_id = $tokenId['sub'];
            $user = User::findOrFail($user_id);
            $roles = $user->roles->toArray();
            if(($roles) > 0){
                $user_roles = [];
                foreach ($roles as $key => $role) {
                    $user_roles[$role['id']] = $role['role_name'];
                }
                $request->attributes->add(
                    [
                        'roles' => $user_roles,
                    ]
                );
                return $next($request);
            } else {
                return response()->json(['error' => 'Forbidden.'], 403);
            }
        }
        catch (ErrorException $e) {
            return response()->json([
                'message' => 'Forbidden.',
                'errors' => [
                    'role' => 'role is not set in header.',
                    'exception' => $e->getMessage(),
                ]
            ], 403);
        }
    }
}
