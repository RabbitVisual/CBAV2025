<?php

namespace Tests\Feature\Admin;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Configuracao;
use App\Services\SystemService;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class SystemServiceTest extends TestCase
{
    use RefreshDatabase;

    protected $adminUser;
    protected $systemService;

    protected function setUp(): void
    {
        parent::setUp();

        // Create roles and permissions
        $role = Role::create(['name' => 'Admin']);
        Permission::create(['name' => 'system.access'])->assignRole($role);

        // Create a user and assign the role
        $this->adminUser = User::factory()->create();
        $this->adminUser->assignRole('Admin');

        $this->systemService = $this->app->make(SystemService::class);
        Storage::fake('public');
    }

    /** @test */
    public function it_can_get_dashboard_data()
    {
        $data = $this->systemService->getDashboardData();

        $this->assertIsArray($data);
        $this->assertArrayHasKey('estatisticas', $data);
        $this->assertArrayHasKey('logsRecentes', $data);
        $this->assertArrayHasKey('sistemaStatus', $data);
        $this->assertEquals(1, $data['estatisticas']['total_usuarios']);
    }

    /** @test */
    public function it_can_get_and_set_system_settings()
    {
        // Set a setting
        $requestData = new \Illuminate\Http\Request([
            'app_name' => 'Test App Name',
            'timezone' => 'UTC',
            'locale' => 'en',
        ]);

        $this->systemService->updateSystemSettings($requestData);

        // Get the setting
        $settings = $this->systemService->getSystemSettings();

        $this->assertEquals('Test App Name', $settings['app_name']);
        $this->assertEquals('UTC', $settings['timezone']);
    }

    /** @test */
    public function it_can_update_system_settings_with_file_uploads()
    {
        $requestData = new \Illuminate\Http\Request([
            'app_name' => 'App With Logo',
            'timezone' => 'UTC',
            'locale' => 'en',
            'app_logo' => UploadedFile::fake()->image('logo.png'),
            'app_favicon' => UploadedFile::fake()->image('favicon.ico'),
        ]);

        $this->systemService->updateSystemSettings($requestData);

        $logoPath = Configuracao::get('app_logo');
        $faviconPath = Configuracao::get('app_favicon');

        $this->assertNotNull($logoPath);
        $this->assertNotNull($faviconPath);

        Storage::disk('public')->assertExists($logoPath);
        Storage::disk('public')->assertExists($faviconPath);
    }

    /** @test */
    public function it_can_get_and_update_home_config()
    {
        $requestData = new \Illuminate\Http\Request([
            'igreja_nome' => 'New Church Name',
            'cor_primaria' => '#FFFFFF',
        ]);

        $this->systemService->updateHomeConfig($requestData);

        $config = $this->systemService->getHomeConfig();

        $this->assertEquals('New Church Name', $config['igreja_nome']);
        $this->assertEquals('#FFFFFF', $config['cor_primaria']);
    }

    /** @test */
    public function it_can_reset_home_config()
    {
        // First, change a value
        Configuracao::set('igreja_nome', 'Custom Name');

        // Then, reset it
        $this->systemService->resetHomeConfig();

        // Check if it has the default value from the seeder
        $this->assertNotEquals('Custom Name', Configuracao::get('igreja_nome'));
    }

    /** @test */
    public function it_can_clear_application_cache()
    {
        // This is hard to test without mocking Artisan facade,
        // but we can at least ensure it runs without errors.
        $this->systemService->clearApplicationCache();
        $this->assertTrue(true); // If no exception is thrown, it's a pass.
    }

    /** @test */
    public function it_can_manage_maintenance_mode()
    {
        $this->assertFalse($this->app->isDownForMaintenance());

        $this->systemService->enableMaintenanceMode();
        $this->assertTrue($this->app->isDownForMaintenance());

        $this->systemService->disableMaintenanceMode();
        $this->assertFalse($this->app->isDownForMaintenance());
    }

    /** @test */
    public function it_can_get_log_data()
    {
        Storage::fake('logs');
        Storage::disk('logs')->put('laravel.log', '[2025-01-01 10:00:00] local.INFO: Test log entry');

        $request = new \Illuminate\Http\Request();
        $logData = $this->systemService->getLogData($request);

        $this->assertIsArray($logData);
        $this->assertArrayHasKey('logs', $logData);
        $this->assertArrayHasKey('laravel.log', $logData['logs']);
        $this->assertCount(1, $logData['logs']['laravel.log']['lines']);
    }

    /** @test */
    public function it_can_clear_logs()
    {
        Storage::fake('logs');
        Storage::disk('logs')->put('laravel.log', 'some log data');

        $this->systemService->clearLogs();

        $this->assertEquals('', Storage::disk('logs')->get('laravel.log'));
    }
}