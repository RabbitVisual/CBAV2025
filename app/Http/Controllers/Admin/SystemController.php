<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\SystemService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class SystemController extends Controller
{
    protected $systemService;

    public function __construct(SystemService $systemService)
    {
        $this->middleware('permission:system.access');
        $this->systemService = $systemService;
    }

    /**
     * Display the system management dashboard.
     */
    public function index()
    {
        $data = $this->systemService->getDashboardData();
        return view('admin.system.dashboard', $data);
    }

    /**
     * Display the system settings page.
     */
    public function settings(Request $request)
    {
        $configuracoes = $this->systemService->getSystemSettings();
        $activeTab = session('active_tab', $request->get('tab', 'geral'));
        return view('admin.system.settings.index', compact('configuracoes', 'activeTab'));
    }

    /**
     * Update system settings.
     */
    public function updateSettings(Request $request)
    {
        try {
            $this->systemService->updateSystemSettings($request);
            $activeTab = $request->input('active_tab', 'geral');

            return redirect()->route('admin.system.settings.index')
                ->with('success', 'Configurações atualizadas com sucesso! O cache foi limpo.')
                ->with('active_tab', $activeTab);
        } catch (ValidationException $e) {
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            Log::error('Erro ao salvar configurações do sistema: ' . $e->getMessage(), [
                'user_id' => auth()->id(),
                'trace' => $e->getTraceAsString()
            ]);
            return redirect()->back()->with('error', 'Erro ao salvar configurações: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Display the homepage configuration page.
     */
    public function homeConfig(Request $request)
    {
        $configuracoes = $this->systemService->getHomeConfig();
        $activeTab = session('active_tab', $request->get('tab', 'basico'));
        return view('admin.system.home-config.index', compact('configuracoes', 'activeTab'));
    }

    /**
     * Update homepage configuration.
     */
    public function updateHomeConfig(Request $request)
    {
        try {
            $this->systemService->updateHomeConfig($request);
            $activeTab = $request->input('active_tab', 'basico');

            return redirect()->route('admin.system.home-config.index')
                ->with('success', 'Configurações da homepage atualizadas com sucesso!')
                ->with('active_tab', $activeTab);
        } catch (ValidationException $e) {
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            Log::error('Erro ao atualizar configurações da homepage: ' . $e->getMessage());
            return redirect()->route('admin.system.home-config.index')->with('error', 'Erro ao salvar configurações: ' . $e->getMessage());
        }
    }

    /**
     * Reset homepage configuration to default values.
     */
    public function resetHomeConfig()
    {
        try {
            $this->systemService->resetHomeConfig();
            return redirect()->route('admin.system.home-config.index')
                ->with('success', 'Configurações da homepage resetadas para os valores padrão!');
        } catch (\Exception $e) {
            Log::error('Erro ao resetar as configurações da homepage: ' . $e->getMessage());
            return redirect()->route('admin.system.home-config.index')
                ->with('error', 'Não foi possível resetar as configurações.');
        }
    }

    /**
     * Display the system logs page.
     */
    public function logs(Request $request)
    {
        $data = $this->systemService->getLogData($request);
        return view('admin.system.logs.index', $data);
    }

    /**
     * Clear log files.
     */
    public function clearLogs()
    {
        try {
            $clearedCount = $this->systemService->clearLogs();
            return response()->json([
                'success' => true,
                'message' => "Logs limpos com sucesso! ({$clearedCount} arquivos)",
                'cleared_count' => $clearedCount
            ]);
        } catch (\Exception $e) {
            Log::error('Erro ao limpar logs: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Erro ao limpar logs.'], 500);
        }
    }

    /**
     * Export log files.
     */
    public function exportLogs(Request $request)
    {
        $logPath = $this->systemService->exportLogs($request);

        if (!$logPath) {
            return response()->json(['error' => 'Arquivo de log não encontrado'], 404);
        }

        $filename = 'log_' . basename($logPath) . '_' . now()->format('Y-m-d') . '.txt';
        return response()->download($logPath, $filename);
    }

    /**
     * Show details of a specific log entry.
     */
    public function showLog(Request $request)
    {
        try {
            $logDetails = $this->systemService->getLogDetails($request);

            if (!$logDetails) {
                return response()->json(['success' => false, 'message' => 'Log não encontrado'], 404);
            }

            $html = view('admin.system.logs.detail', ['log' => $logDetails])->render();
            return response()->json(['success' => true, 'content' => $html]);

        } catch (\Exception $e) {
            Log::error('Erro ao exibir detalhes do log: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Erro ao processar o log.'], 500);
        }
    }

    /**
     * Display the system maintenance page.
     */
    public function maintenance()
    {
        $status = $this->systemService->getMaintenanceStatus();
        return view('admin.system.maintenance.index', compact('status'));
    }

    /**
     * Enable maintenance mode.
     */
    public function enableMaintenance()
    {
        $this->systemService->enableMaintenanceMode();
        return redirect()->route('admin.system.maintenance.index')
            ->with('success', 'Modo manutenção ativado com sucesso!');
    }

    /**
     * Disable maintenance mode.
     */
    public function disableMaintenance()
    {
        $this->systemService->disableMaintenanceMode();
        return redirect()->route('admin.system.maintenance.index')
            ->with('success', 'Modo manutenção desativado com sucesso!');
    }

    /**
     * Run a database backup.
     */
    public function runBackup()
    {
        $result = $this->systemService->runBackup();
        return response()->json($result);
    }

    /**
     * Clear all application caches.
     */
    public function clearCache()
    {
        try {
            $this->systemService->clearApplicationCache();
            return response()->json(['success' => true, 'message' => 'Cache limpo com sucesso!']);
        } catch (\Exception $e) {
            Log::error('Erro ao limpar cache via controller: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Erro ao limpar o cache.'], 500);
        }
    }
}