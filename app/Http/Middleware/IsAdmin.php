<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        //VERIFICAR SI EL USUARIO AUTENTICADO ES ADMINISTRADOR
        if ($request->user()->is_admin == 0) {
            //SI NO ES ADMINISTRADOR, REDIRIGIR A LA PÁGINA DE INICIO
            return redirect()->route('home')->with('error', 'No tienes permisos para acceder a esta sección.');
        }
        return $next($request);
    }
}
