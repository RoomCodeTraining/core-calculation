<?php

use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // Le middleware EnsureFrontendRequestsAreStateful est retiré des routes API
        // car il est conçu pour les SPA avec cookies, pas pour les API avec tokens Bearer
        // $middleware->api(prepend: [
        //     \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,
        // ]);

        $middleware->alias([
            'verified' => \App\Http\Middleware\EnsureEmailIsVerified::class,
            'check.api.quota' => \App\Http\Middleware\CheckApiQuota::class,
        ]);

        //
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        // Pour les routes API, retourner une réponse JSON 401 au lieu de rediriger vers login
        $exceptions->shouldRenderJsonWhen(function (Request $request, Throwable $e) {
            if ($request->is('api/*')) {
                return true;
            }

            return $request->expectsJson();
        });

        // Gérer spécifiquement les exceptions d'authentification pour les routes API
        $exceptions->render(function (AuthenticationException $e, Request $request) {
            if ($request->is('api/*') || $request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Non authentifié. Token d\'accès requis.',
                ], 401);
            }
        });
    })->create();
