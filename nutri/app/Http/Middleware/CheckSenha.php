<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckSenha
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
        if (!$request->session()->has('senha')) {
            // Redirecionar para a página anterior ou para uma página específica se o email não estiver na sessão
            return redirect()->route('regis.senha')->with(['error' => 'Acesso negado. Por favor, preencha a senha.',]);
        }

        return $next($request);
    }
}