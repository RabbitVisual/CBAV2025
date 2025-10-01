<?php

namespace App\Http\View\Composers;

use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use App\Models\Membro;
use App\Models\EbdAluno;
use App\Helpers\PermissionHelper;

class MemberLayoutComposer
{
    /**
     * Bind data to the view.
     */
    public function compose(View $view): void
    {
        if (!Auth::check()) {
            // Se o usuário não estiver autenticado, não há dados para carregar.
            $view->with([
                'membro' => null,
                'alunoEbd' => null,
                'unreadNotificationsCount' => 0,
                'latestNotifications' => collect(),
                'hasAdminAccess' => false,
                'currentRouteName' => '',
                'currentSection' => '',
            ]);
            return;
        }

        $user = Auth::user();

        // Buscar dados do membro e do aluno EBD uma única vez
        $membro = Membro::where('user_id', $user->id)->first();
        $alunoEbd = $membro ? EbdAluno::where('membro_id', $membro->id)->where('status', 'ativo')->with('turma')->first() : null;

        // Buscar dados de notificação
        $unreadNotificationsCount = $user->unreadNotifications()->count();
        $latestNotifications = $user->notifications()->latest()->limit(5)->get();

        // Verificar permissões de acesso
        $hasAdminAccess = PermissionHelper::hasAdminAccess();

        // Determinar a seção ativa do menu
        $currentRouteName = request()->route()->getName();
        $currentSection = $this->getCurrentSection($currentRouteName);

        // Compartilhar os dados com a view
        $view->with(compact(
            'membro',
            'alunoEbd',
            'unreadNotificationsCount',
            'latestNotifications',
            'hasAdminAccess',
            'currentRouteName',
            'currentSection'
        ));
    }

    /**
     * Determina a seção atual do menu com base no nome da rota.
     */
    private function getCurrentSection(string $routeName): string
    {
        $sections = [
            'dashboard' => 'member.dashboard',
            'profile' => 'member.profile',
            'devotionals' => 'member.devotionals',
            'bible' => 'member.bible',
            'prayer' => 'member.pedidos-oracao',
            'events' => 'member.eventos',
            'ministries' => 'member.ministries',
            'ebd' => 'member.ebd',
            'donations' => 'member.donations',
            'chat' => 'member.chat',
            'notifications' => 'member.notifications',
        ];

        foreach ($sections as $section => $prefix) {
            if (str_starts_with($routeName, $prefix)) {
                return $section;
            }
        }

        return '';
    }
}