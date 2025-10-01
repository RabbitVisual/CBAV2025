<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Permission\Models\Permission as SpatiePermission;

class Permission extends SpatiePermission
{
    use HasFactory;

    protected $fillable = [
        'name',
        'display_name',
        'description',
        'module',
        'action',
        'resource',
        'is_active',
        'priority'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'priority' => 'integer'
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByModule($query, $module)
    {
        return $query->where('module', $module);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('priority', 'desc')->orderBy('name');
    }

    public function getDisplayNameAttribute($value)
    {
        return $value ?: $this->name;
    }

    public function getModuleDisplayNameAttribute()
    {
        $modules = [
            'members' => 'Membros',
            'ministries' => 'Ministérios',
            'departments' => 'Departamentos',
            'positions' => 'Cargos',
            'campaigns' => 'Campanhas',
            'transactions' => 'Transações',
            'notifications' => 'Notificações',
            'system' => 'Sistema',
            'reports' => 'Relatórios',
            'users' => 'Usuários'
        ];

        return $modules[$this->module] ?? ucfirst($this->module);
    }

    public function getActionDisplayNameAttribute()
    {
        $actions = [
            'create' => 'Criar',
            'read' => 'Visualizar',
            'update' => 'Editar',
            'delete' => 'Excluir',
            'export' => 'Exportar',
            'import' => 'Importar',
            'manage' => 'Gerenciar',
            'approve' => 'Aprovar',
            'reject' => 'Rejeitar'
        ];

        return $actions[$this->action] ?? ucfirst($this->action);
    }
} 