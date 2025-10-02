<?php

namespace Tests\Feature\Admin;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Profile;
use App\Services\ProfileService;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Carbon\Carbon;

class ProfileServiceTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected $adminUser;
    protected $profileService;

    protected function setUp(): void
    {
        parent::setUp();

        $role = Role::create(['name' => 'Admin']);
        Permission::create(['name' => 'profile.edit'])->assignRole($role);

        $this->adminUser = User::factory()->create();
        $this->adminUser->assignRole('Admin');

        $this->profileService = $this->app->make(ProfileService::class);
        Storage::fake('public');
    }

    /** @test */
    public function it_can_create_a_profile_for_a_user()
    {
        $user = User::factory()->create();
        $profileData = [
            'telefone' => $this->faker->phoneNumber,
            'data_nascimento' => '1990-01-01',
            'estado_civil' => 'solteiro',
        ];

        $request = new Request($profileData);

        $profile = $this->profileService->createProfile($user, $request);

        $this->assertInstanceOf(Profile::class, $profile);
        $this->assertDatabaseHas('profiles', [
            'user_id' => $user->id,
            'telefone' => $profileData['telefone']
        ]);
    }

    /** @test */
    public function it_can_update_a_profile()
    {
        $user = User::factory()->create();
        $profile = Profile::factory()->create(['user_id' => $user->id]);

        $updateData = [
            'telefone' => '11987654321',
            'cidade' => 'São Paulo',
        ];
        $request = new Request($updateData);

        $updatedProfile = $this->profileService->updateProfile($profile, $request);

        $this->assertEquals('11987654321', $updatedProfile->telefone);
        $this->assertDatabaseHas('profiles', [
            'id' => $profile->id,
            'telefone' => '11987654321',
        ]);
    }

    /** @test */
    public function it_can_update_a_profile_with_a_photo()
    {
        $user = User::factory()->create();
        $profile = Profile::factory()->create(['user_id' => $user->id]);
        $file = UploadedFile::fake()->image('avatar.jpg');

        $request = new Request(['foto' => $file]);

        $this->profileService->updateProfile($profile, $request);

        $profile->refresh();
        $this->assertNotNull($profile->foto);
        Storage::disk('public')->assertExists($profile->foto);
    }

    /** @test */
    public function it_deletes_old_photo_when_a_new_one_is_uploaded()
    {
        $user = User::factory()->create();
        $oldFile = UploadedFile::fake()->image('old_avatar.jpg');
        $oldPath = $oldFile->store('profiles/fotos', 'public');

        $profile = Profile::factory()->create([
            'user_id' => $user->id,
            'foto' => $oldPath
        ]);

        $newFile = UploadedFile::fake()->image('new_avatar.jpg');
        $request = new Request(['foto' => $newFile]);

        $this->profileService->updateProfile($profile, $request);

        Storage::disk('public')->assertMissing($oldPath);
        Storage::disk('public')->assertExists($profile->fresh()->foto);
    }

    /** @test */
    public function it_can_get_birthday_people_of_the_month()
    {
        $user1 = User::factory()->create();
        Profile::factory()->create([
            'user_id' => $user1->id,
            'data_nascimento' => Carbon::now()->startOfMonth()
        ]);

        $user2 = User::factory()->create();
        Profile::factory()->create([
            'user_id' => $user2->id,
            'data_nascimento' => Carbon::now()->endOfMonth()
        ]);

        $user3 = User::factory()->create();
        Profile::factory()->create([
            'user_id' => $user3->id,
            'data_nascimento' => Carbon::now()->subMonth()
        ]);

        $aniversariantes = $this->profileService->getAniversariantesDoMes();

        $this->assertCount(2, $aniversariantes);
        $this->assertTrue($aniversariantes->contains($user1));
        $this->assertTrue($aniversariantes->contains($user2));
        $this->assertFalse($aniversariantes->contains($user3));
    }

    /** @test */
    public function it_can_get_recent_members()
    {
        $user1 = User::factory()->create();
        Profile::factory()->create([
            'user_id' => $user1->id,
            'data_ingresso' => Carbon::now()->subDays(10)
        ]);

        $user2 = User::factory()->create();
        Profile::factory()->create([
            'user_id' => $user2->id,
            'data_ingresso' => Carbon::now()->subDays(40)
        ]);

        $recentes = $this->profileService->getMembrosRecentes(30);

        $this->assertCount(1, $recentes);
        $this->assertEquals($user1->id, $recentes->first()->id);
    }
}