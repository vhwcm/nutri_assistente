<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    public function render($request, Throwable $exception)
    {
        \Log::error($exception);

        // Tratamento específico para MethodNotAllowedHttpException
        if ($exception instanceof \Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException) {
            return back()->with('error', 'Erro');
        }

        // Responder com uma mensagem genérica para qualquer outra exceção não tratada explicitamente
        if ($this->isHttpException($exception)) {
            return back()->with('error', 'Erro');
        } else {
            return back()->with('error', 'Erro');
        }

    }
}

