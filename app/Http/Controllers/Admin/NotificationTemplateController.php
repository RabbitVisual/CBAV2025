<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use App\Models\NotificationTemplate;
use App\Services\NotificationService;

class NotificationTemplateController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('can:manage-notification-templates');
    }

    /**
     * Exibir lista de templates
     */
    public function index(Request $request)
    {
        $query = NotificationTemplate::query();

        // Aplicar filtros
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('status')) {
            $active = $request->status === 'active';
            $query->where('is_active', $active);
        }

        $templates = $query->orderBy('name')->paginate(15);

        $stats = [
            'total' => NotificationTemplate::count(),
            'active' => NotificationTemplate::where('is_active', true)->count(),
            'inactive' => NotificationTemplate::where('is_active', false)->count(),
            'by_category' => NotificationTemplate::selectRaw('category, count(*) as count')
                                               ->groupBy('category')
                                               ->pluck('count', 'category')
        ];

        return view('admin.notification-templates.index', compact('templates', 'stats'));
    }

    /**
     * Exibir formulário de criação
     */
    public function create()
    {
        $categories = $this->getCategories();
        $types = $this->getTypes();
        $priorities = $this->getPriorities();
        $channels = $this->getChannels();

        return view('admin.notification-templates.create', compact('categories', 'types', 'priorities', 'channels'));
    }

    /**
     * Salvar novo template
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:100|unique:notification_templates,name',
            'title' => 'required|string|max:255',
            'message_template' => 'required|string|max:1000',
            'type' => 'required|in:info,success,warning,error,urgent',
            'category' => 'required|string|max:50',
            'priority' => 'required|in:low,normal,high,urgent',
            'icon' => 'nullable|string|max:100',
            'color' => 'nullable|string|max:50',
            'default_channels' => 'array',
            'default_channels.*' => 'in:database,email,push,sms',
            'variables' => 'array',
            'variables.*.name' => 'required|string|max:50',
            'variables.*.type' => 'required|in:string,number,date,time,datetime,boolean',
            'variables.*.description' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:500',
            'is_active' => 'boolean'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        try {
            $template = NotificationTemplate::create([
                'name' => $request->name,
                'title' => $request->title,
                'message_template' => $request->message_template,
                'type' => $request->type,
                'category' => $request->category,
                'priority' => $request->priority,
                'icon' => $request->icon,
                'color' => $request->color,
                'default_channels' => $request->default_channels ?? ['database'],
                'variables' => $this->processVariables($request->variables ?? []),
                'description' => $request->description,
                'is_active' => $request->boolean('is_active', true)
            ]);

            return redirect()->route('admin.notification-templates.index')
                           ->with('success', 'Template criado com sucesso!');
        } catch (\Exception $e) {
            Log::error('Error creating notification template: ' . $e->getMessage());
            return back()->with('error', 'Erro ao criar template.')->withInput();
        }
    }

    /**
     * Exibir template específico
     */
    public function show(NotificationTemplate $notificationTemplate)
    {
        $preview = $notificationTemplate->preview;
        $usageStats = $this->getTemplateUsageStats($notificationTemplate);

        return view('admin.notification-templates.show', compact('notificationTemplate', 'preview', 'usageStats'));
    }

    /**
     * Exibir formulário de edição
     */
    public function edit(NotificationTemplate $notificationTemplate)
    {
        $categories = $this->getCategories();
        $types = $this->getTypes();
        $priorities = $this->getPriorities();
        $channels = $this->getChannels();

        return view('admin.notification-templates.edit', compact('notificationTemplate', 'categories', 'types', 'priorities', 'channels'));
    }

    /**
     * Atualizar template
     */
    public function update(Request $request, NotificationTemplate $notificationTemplate)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:100|unique:notification_templates,name,' . $notificationTemplate->id,
            'title' => 'required|string|max:255',
            'message_template' => 'required|string|max:1000',
            'type' => 'required|in:info,success,warning,error,urgent',
            'category' => 'required|string|max:50',
            'priority' => 'required|in:low,normal,high,urgent',
            'icon' => 'nullable|string|max:100',
            'color' => 'nullable|string|max:50',
            'default_channels' => 'array',
            'default_channels.*' => 'in:database,email,push,sms',
            'variables' => 'array',
            'variables.*.name' => 'required|string|max:50',
            'variables.*.type' => 'required|in:string,number,date,time,datetime,boolean',
            'variables.*.description' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:500',
            'is_active' => 'boolean'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        try {
            $notificationTemplate->update([
                'name' => $request->name,
                'title' => $request->title,
                'message_template' => $request->message_template,
                'type' => $request->type,
                'category' => $request->category,
                'priority' => $request->priority,
                'icon' => $request->icon,
                'color' => $request->color,
                'default_channels' => $request->default_channels ?? ['database'],
                'variables' => $this->processVariables($request->variables ?? []),
                'description' => $request->description,
                'is_active' => $request->boolean('is_active', true)
            ]);

            return redirect()->route('admin.notification-templates.index')
                           ->with('success', 'Template atualizado com sucesso!');
        } catch (\Exception $e) {
            Log::error('Error updating notification template: ' . $e->getMessage());
            return back()->with('error', 'Erro ao atualizar template.')->withInput();
        }
    }

    /**
     * Excluir template
     */
    public function destroy(NotificationTemplate $notificationTemplate)
    {
        try {
            $notificationTemplate->delete();
            return redirect()->route('admin.notification-templates.index')
                           ->with('success', 'Template excluído com sucesso!');
        } catch (\Exception $e) {
            Log::error('Error deleting notification template: ' . $e->getMessage());
            return back()->with('error', 'Erro ao excluir template.');
        }
    }

    /**
     * Ativar/desativar template
     */
    public function toggleStatus(NotificationTemplate $notificationTemplate)
    {
        try {
            $newStatus = !$notificationTemplate->is_active;
            $notificationTemplate->update(['is_active' => $newStatus]);

            $status = $newStatus ? 'ativado' : 'desativado';
            
            return response()->json([
                'success' => true,
                'message' => "Template {$status} com sucesso!",
                'is_active' => $newStatus
            ]);
        } catch (\Exception $e) {
            Log::error('Error toggling template status: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Erro ao alterar status do template.'
            ], 500);
        }
    }

    /**
     * Duplicar template
     */
    public function duplicate(NotificationTemplate $notificationTemplate)
    {
        try {
            $newTemplate = $notificationTemplate->replicate();
            $newTemplate->name = $notificationTemplate->name . '_copy_' . time();
            $newTemplate->is_active = false;
            $newTemplate->save();

            return redirect()->route('admin.notification-templates.edit', $newTemplate)
                           ->with('success', 'Template duplicado com sucesso! Edite conforme necessário.');
        } catch (\Exception $e) {
            Log::error('Error duplicating template: ' . $e->getMessage());
            return back()->with('error', 'Erro ao duplicar template.');
        }
    }

    /**
     * Testar template
     */
    public function test(Request $request, NotificationTemplate $notificationTemplate)
    {
        $validator = Validator::make($request->all(), [
            'test_variables' => 'array',
            'recipient_id' => 'nullable|exists:users,id'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Dados inválidos.',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $variables = $request->test_variables ?? [];
            $recipientId = $request->recipient_id ?? Auth::id();

            // Validar variáveis obrigatórias
            $missing = $notificationTemplate->validateVariables($variables);
            if (!empty($missing)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Variáveis obrigatórias não fornecidas: ' . implode(', ', $missing)
                ], 422);
            }

            // Criar notificação de teste
            $notification = $notificationTemplate->createNotification($variables, [
                'recipient_type' => 'user',
                'recipient_id' => $recipientId,
                'sender_id' => Auth::id()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Notificação de teste enviada com sucesso!',
                'notification_id' => $notification->id,
                'preview' => $notificationTemplate->render($variables)
            ]);
        } catch (\Exception $e) {
            Log::error('Error testing template: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Erro ao testar template: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Obter preview do template
     */
    public function preview(Request $request, NotificationTemplate $notificationTemplate)
    {
        try {
            $variables = $request->variables ?? [];
            $preview = $notificationTemplate->render($variables);

            return response()->json([
                'success' => true,
                'preview' => $preview
            ]);
        } catch (\Exception $e) {
            Log::error('Error generating preview: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Erro ao gerar preview.'
            ], 500);
        }
    }

    /**
     * Exportar templates
     */
    public function export(Request $request)
    {
        try {
            $format = $request->get('format', 'json');
            $templates = NotificationTemplate::all();

            if ($format === 'json') {
                $data = $templates->map(function ($template) {
                    return $template->only([
                        'name', 'title', 'message_template', 'type', 'category',
                        'priority', 'icon', 'color', 'default_channels', 'variables',
                        'description', 'is_active'
                    ]);
                });

                $filename = 'notification_templates_' . date('Y-m-d_H-i-s') . '.json';
                
                return response()->json($data)
                                ->header('Content-Disposition', "attachment; filename={$filename}");
            }

            return back()->with('error', 'Formato de exportação não suportado.');
        } catch (\Exception $e) {
            Log::error('Error exporting templates: ' . $e->getMessage());
            return back()->with('error', 'Erro ao exportar templates.');
        }
    }

    /**
     * Importar templates
     */
    public function import(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'import_file' => 'required|file|mimes:json|max:2048'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator);
        }

        try {
            $file = $request->file('import_file');
            $content = file_get_contents($file->getRealPath());
            $templates = json_decode($content, true);

            if (!is_array($templates)) {
                return back()->with('error', 'Arquivo de importação inválido.');
            }

            $imported = 0;
            $errors = [];

            foreach ($templates as $templateData) {
                try {
                    // Verificar se já existe
                    $existing = NotificationTemplate::where('name', $templateData['name'])->first();
                    if ($existing) {
                        $templateData['name'] .= '_imported_' . time();
                    }

                    NotificationTemplate::create($templateData);
                    $imported++;
                } catch (\Exception $e) {
                    $errors[] = "Erro ao importar template '{$templateData['name']}': " . $e->getMessage();
                }
            }

            $message = "{$imported} template(s) importado(s) com sucesso!";
            if (!empty($errors)) {
                $message .= ' Erros: ' . implode(', ', $errors);
            }

            return back()->with('success', $message);
        } catch (\Exception $e) {
            Log::error('Error importing templates: ' . $e->getMessage());
            return back()->with('error', 'Erro ao importar templates.');
        }
    }

    /**
     * Instalar templates padrão
     */
    public function installDefaults()
    {
        try {
            $installed = 0;
            
            // Lista de templates padrão para instalar
            $defaultTemplates = [
                'createQuizRecordTemplate',
                'createQuizCompletionTemplate',
                'createSystemMaintenanceTemplate',
                'createNewMemberTemplate',
                'createEventReminderTemplate'
            ];

            foreach ($defaultTemplates as $method) {
                try {
                    if (method_exists(NotificationTemplate::class, $method)) {
                        NotificationTemplate::$method();
                        $installed++;
                    }
                } catch (\Exception $e) {
                    Log::warning("Error installing default template {$method}: " . $e->getMessage());
                }
            }

            return response()->json([
                'success' => true,
                'message' => "{$installed} template(s) padrão instalado(s) com sucesso!",
                'count' => $installed
            ]);
        } catch (\Exception $e) {
            Log::error('Error installing default templates: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Erro ao instalar templates padrão.'
            ], 500);
        }
    }

    // Métodos auxiliares privados
    private function getCategories(): array
    {
        return [
            'system' => 'Sistema',
            'quiz' => 'Quiz Bíblico',
            'ministry' => 'Ministério',
            'financial' => 'Financeiro',
            'event' => 'Eventos',
            'member' => 'Membros'
        ];
    }

    private function getTypes(): array
    {
        return [
            'info' => 'Informação',
            'success' => 'Sucesso',
            'warning' => 'Aviso',
            'error' => 'Erro',
            'urgent' => 'Urgente'
        ];
    }

    private function getPriorities(): array
    {
        return [
            'low' => 'Baixa',
            'normal' => 'Normal',
            'high' => 'Alta',
            'urgent' => 'Urgente'
        ];
    }

    private function getChannels(): array
    {
        return [
            'database' => 'Banco de Dados',
            'email' => 'E-mail',
            'push' => 'Push',
            'sms' => 'SMS'
        ];
    }

    private function processVariables(array $variables): array
    {
        return array_map(function ($variable) {
            return [
                'name' => $variable['name'],
                'type' => $variable['type'],
                'description' => $variable['description'] ?? ''
            ];
        }, $variables);
    }

    private function getTemplateUsageStats(NotificationTemplate $template): array
    {
        // Implementar estatísticas de uso do template
        return [
            'total_uses' => 0, // Implementar contagem real
            'last_used' => null, // Implementar data do último uso
            'success_rate' => 0 // Implementar taxa de sucesso
        ];
    }
}