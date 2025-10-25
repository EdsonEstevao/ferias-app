<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class TrackUserActivity
{
    /**
     * Rotas que não devem rastrear atividade
     */
    protected array $except = [
        'horizon/*',
        'telescope/*',
        'api/health',
        'up',
        'ping',
    ];


    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // verificar se a rota está na lista de exceções
        if($this->inExceptArray($request)) {
            return $next($request);
        }


        // rastrear atividade se usuario estiver autenticado

        if(Auth::check()) {
            $user = Auth::user();

            // usar queue para não bloquear o response
            // dispatch(function () use ($user, $request) {
            //     $user->updateLastActivity(
            //         $request->ip(),
            //         $request->userAgent()
            //     );
            // })->afterResponse(); // executa depois da response

            dispatch_sync(function () use ($user, $request) {

                try {
                    // opção 1: com ip e user agent
                    $user->updateLastActivityWithDetails(
                        $request->ip(),
                        substr($request->userAgent() ?? '', 0, 500)
                    );

                    // ou opção 2: apenas atualizar atividade (mais simples)
                    // $user->updateLastActivity();


                    // $user->updateLastActivity(
                    //     $request->ip(),
                    //     $request->userAgent(),
                    //     substr($request->userAgent() ?? '', 0, 500) // limita o tamanho do user agent
                    // );
                } catch (\Exception $e ) {
                    // logar erro sem interromper a aplicação
                    Log::error('Erro ao rastrear atividade do usuário: ' . $e->getMessage());

                }
            });

        }

        return $next($request);
    }

    /**
     * Determine se a solicitação tiver um URI que deve passar.
     *
     */

    protected function inExceptArray(Request $request): bool
    {
        foreach ($this->except as $except) {
            if ($except !== '/')
            {
                $except = trim($except, '/');
            }

            if ($request->is($except)) {
                return true;
            }
        }

        return false;
    }
}
