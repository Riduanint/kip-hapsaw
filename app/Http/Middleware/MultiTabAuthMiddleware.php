<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class MultiTabAuthMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $sk = $request->query('sk') ?? $request->input('sk');

        if (!$sk) {
            if (Auth::check()) {
                $newSk = 'tab_' . uniqid();
                session(["multi_auth.{$newSk}" => Auth::id()]);
                
                $query = $request->query();
                $query['sk'] = $newSk;
                
                return redirect()->to($request->getPathInfo() . '?' . http_build_query($query));
            }
            return redirect()->route('login')->with('warning', 'Akses dibatasi. Silakan login untuk membuka sesi di tab baru.');
        }

        $userId = session("multi_auth.{$sk}");

        if (!$userId) {
            return redirect()->route('login')->with('error', 'Sesi tab Anda telah kedaluwarsa atau tidak valid.');
        }

        // Log in the user for this specific request lifecycle
        Auth::loginUsingId($userId);

        // Share the session key with all views globally
        view()->share('sk', $sk);

        return $next($request);
    }
}
