<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class LogHelper
{
    /**
     * Registrar log de segurança
     */
    public static function security($message, $context = [])
    {
        $context['user_id'] = Auth::id();
        $context['ip'] = request()->ip();
        $context['user_agent'] = request()->userAgent();
        $context['url'] = request()->fullUrl();
        
        Log::channel('security')->warning($message, $context);
    }

    /**
     * Registrar log de auditoria
     */
    public static function audit($action, $model = null, $context = [])
    {
        $context['user_id'] = Auth::id();
        $context['action'] = $action;
        $context['model'] = $model;
        $context['ip'] = request()->ip();
        $context['url'] = request()->fullUrl();
        
        Log::channel('audit')->info("Audit: {$action}", $context);
    }

    /**
     * Registrar log de performance
     */
    public static function performance($operation, $duration, $context = [])
    {
        $context['duration'] = $duration;
        $context['operation'] = $operation;
        $context['memory'] = memory_get_usage(true);
        
        Log::channel('performance')->info("Performance: {$operation} ({$duration}ms)", $context);
    }

    /**
     * Registrar log crítico
     */
    public static function critical($message, $context = [])
    {
        $context['user_id'] = Auth::id();
        $context['ip'] = request()->ip();
        $context['url'] = request()->fullUrl();
        
        Log::channel('critical')->critical($message, $context);
    }

    /**
     * Registrar erro com contexto completo
     */
    public static function error($message, $exception = null, $context = [])
    {
        $context['user_id'] = Auth::id();
        $context['ip'] = request()->ip();
        $context['url'] = request()->fullUrl();
        $context['method'] = request()->method();
        
        if ($exception) {
            $context['exception'] = [
                'message' => $exception->getMessage(),
                'file' => $exception->getFile(),
                'line' => $exception->getLine(),
                'trace' => $exception->getTraceAsString()
            ];
        }
        
        Log::error($message, $context);
    }

    /**
     * Registrar acesso a área administrativa
     */
    public static function adminAccess($action, $context = [])
    {
        $context['user_id'] = Auth::id();
        $context['action'] = $action;
        $context['ip'] = request()->ip();
        $context['url'] = request()->fullUrl();
        
        Log::channel('audit')->info("Admin Access: {$action}", $context);
    }

    /**
     * Registrar tentativa de login
     */
    public static function loginAttempt($email, $success, $context = [])
    {
        $context['email'] = $email;
        $context['success'] = $success;
        $context['ip'] = request()->ip();
        $context['user_agent'] = request()->userAgent();
        
        Log::channel('security')->info("Login Attempt: " . ($success ? 'SUCCESS' : 'FAILED'), $context);
    }

    /**
     * Registrar operação financeira
     */
    public static function financial($operation, $amount, $context = [])
    {
        $context['operation'] = $operation;
        $context['amount'] = $amount;
        $context['user_id'] = Auth::id();
        $context['ip'] = request()->ip();
        
        Log::channel('audit')->info("Financial: {$operation} - R$ {$amount}", $context);
    }

    /**
     * Registrar operação de dados pessoais
     */
    public static function personalData($operation, $dataType, $context = [])
    {
        $context['operation'] = $operation;
        $context['data_type'] = $dataType;
        $context['user_id'] = Auth::id();
        $context['ip'] = request()->ip();
        
        Log::channel('audit')->info("Personal Data: {$operation} - {$dataType}", $context);
    }

    /**
     * Registrar operação de sistema
     */
    public static function system($operation, $context = [])
    {
        $context['operation'] = $operation;
        $context['user_id'] = Auth::id();
        $context['ip'] = request()->ip();
        
        Log::channel('audit')->info("System: {$operation}", $context);
    }
} 