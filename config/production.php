<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Configurações de Produção - CBAV CRM Ministerial
    |--------------------------------------------------------------------------
    |
    | Configurações otimizadas para ambiente de produção
    | Desenvolvido por Vertex Solutions - CEO Reinan Rodrigues
    |
    */

    // Configurações de aplicação
    "app" => [
        "env" => "production",
        "debug" => false,
        "url" => env("APP_URL", "https://seudominio.com"),
        "timezone" => "America/Sao_Paulo",
        "locale" => "pt_BR",
        "fallback_locale" => "pt_BR",
        "faker_locale" => "pt_BR",
    ],

    // Configurações de log
    "logging" => [
        "default" => "stack",
        "channels" => [
            "stack" => [
                "driver" => "stack",
                "channels" => ["single"],
                "ignore_exceptions" => false,
            ],
            "single" => [
                "driver" => "single",
                "path" => base_path("storage/logs/laravel.log"),
                "level" => "error",
            ],
        ],
        "level" => "error",
        "deprecations" => null,
    ],

    // Configurações de cache
    "cache" => [
        "default" => "file",
        "stores" => [
            "file" => [
                "driver" => "file",
                "path" => base_path("storage/framework/cache/data"),
            ],
        ],
    ],

    // Configurações de sessão
    "session" => [
        "driver" => "database",
        "lifetime" => 120,
        "expire_on_close" => false,
        "encrypt" => false,
        "secure" => true,
        "http_only" => true,
        "same_site" => "lax",
    ],

    // Configurações de filas
    "queue" => [
        "default" => "database",
        "connections" => [
            "database" => [
                "driver" => "database",
                "table" => "jobs",
                "queue" => "default",
                "retry_after" => 90,
                "after_commit" => false,
            ],
        ],
        "failed" => [
            "driver" => "database-uuids",
            "database" => env("DB_CONNECTION", "mysql"),
            "table" => "failed_jobs",
        ],
    ],

    // Configurações de segurança
    "security" => [
        "bcrypt_rounds" => 12,
        "session_secure" => true,
        "session_http_only" => true,
        "session_same_site" => "lax",
        "csrf_token_lifetime" => 120,
    ],

    // Configurações de performance
    "performance" => [
        "cache_views" => true,
        "cache_config" => true,
        "cache_routes" => true,
        "optimize_autoloader" => true,
        "compress_output" => true,
    ],

    // Configurações de backup
    "backup" => [
        "enabled" => true,
        "disk" => "local",
        "retention_days" => 30,
        "compress" => true,
        "notify_on_failure" => true,
    ],

    // Configurações de monitoramento
    "monitoring" => [
        "enabled" => true,
        "log_errors" => true,
        "log_queries" => false,
        "log_requests" => false,
        "performance_monitoring" => true,
    ],

    // Configurações de manutenção
    "maintenance" => [
        "enabled" => false,
        "allowed_ips" => [],
        "secret" => env("APP_MAINTENANCE_SECRET"),
    ],
];
