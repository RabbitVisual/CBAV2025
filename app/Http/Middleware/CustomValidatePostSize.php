<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Log;

class CustomValidatePostSize
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $max = $this->getPostMaxSize();
        $contentLength = $request->server('CONTENT_LENGTH');

        // Log para debug
        Log::info('CustomValidatePostSize', [
            'content_length' => $contentLength,
            'max_size' => $max,
            'url' => $request->url(),
            'method' => $request->method()
        ]);

        // Se não há content length, permite a requisição
        if (!$contentLength) {
            return $next($request);
        }

        // Se o tamanho máximo é 0 (ilimitado), permite a requisição
        if ($max <= 0) {
            return $next($request);
        }

        // Se a requisição excede o limite
        if ($contentLength > $max) {
            Log::warning('Request size exceeded limit', [
                'content_length' => $contentLength,
                'max_size' => $max,
                'url' => $request->url()
            ]);

            // Se a requisição for AJAX, retorna JSON
            if ($request->expectsJson()) {
                return response()->json([
                    'error' => 'Tamanho da requisição excede o limite permitido.',
                    'max_size' => $this->formatBytes($max),
                    'current_size' => $this->formatBytes($contentLength)
                ], 413);
            }

            // Se não for AJAX, retorna uma view de erro
            abort(413, 'Tamanho da requisição excede o limite permitido. Máximo: ' . $this->formatBytes($max));
        }

        return $next($request);
    }

    /**
     * Get the maximum POST size in bytes.
     */
    protected function getPostMaxSize(): int
    {
        $max = ini_get('post_max_size');

        if (! $max) {
            return 0;
        }

        switch (strtolower(substr($max, -1))) {
            case 'g':
                $max = (int) $max * 1024;
            case 'm':
                $max = (int) $max * 1024;
            case 'k':
                $max = (int) $max * 1024;
        }

        return $max;
    }

    /**
     * Format bytes to human readable format.
     */
    protected function formatBytes(int $bytes): string
    {
        $units = ['B', 'KB', 'MB', 'GB'];

        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }

        return round($bytes, 2) . ' ' . $units[$i];
    }
} 