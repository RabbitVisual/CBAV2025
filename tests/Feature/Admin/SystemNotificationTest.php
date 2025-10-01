<?php

namespace Tests\Feature\Admin;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;
use App\Models\User;
use App\Models\Role;
use App\Notifications\GeneralPurposeNotification;

class SystemNotificationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Garante que os papéis necessários existam
        Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        Role::firstOrCreate(['name' => 'member', 'guard_name' => 'web']);
    }

    /**
     * Testa se um administrador pode enviar uma notificação para um usuário específico.
     *
     * @return void
     */
    public function test_admin_can_send_notification_to_specific_user()
    {
        // Impede o envio real de notificações para não sobrecarregar o log
        Notification::fake();

        // Cria um usuário admin e um usuário alvo
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        $targetUser = User::factory()->create();
        $targetUser->assignRole('member');

        // Autentica como admin
        $this->actingAs($admin);

        // Dados da notificação
        $notificationData = [
            'title' => 'Teste de Notificação',
            'message' => 'Esta é uma mensagem de teste.',
            'type' => 'info',
            'recipient_type' => 'user',
            'user_id' => $targetUser->id,
        ];

        // Envia a requisição para criar a notificação
        $response = $this->post(route('admin.system.notifications.store'), $notificationData);

        // Verifica o redirecionamento e a mensagem de sucesso
        $response->assertRedirect(route('admin.system.notifications.index'));
        $response->assertSessionHas('success', 'Notificação enviada com sucesso!');

        // Verifica se a notificação foi enviada para o usuário correto
        Notification::assertSentTo(
            [$targetUser],
            GeneralPurposeNotification::class,
            function ($notification, $channels) use ($notificationData) {
                return $notification->title === $notificationData['title'];
            }
        );
    }
}