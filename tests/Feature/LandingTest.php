<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LandingTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function calon_can_register_and_redirect_to_dashboard()
    {
        $this->seed(\Database\Seeders\UserAdminSeeder::class);

        $res = $this->post('/register-calon', [
            'name' => 'Calon 1',
            'email'=> 'calon1@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $res->assertRedirect(route('pendaftaran.dashboard'));
        $this->assertDatabaseHas('users', ['email'=>'calon1@example.com','role'=>'calon_mahasiswa']);
        $this->assertDatabaseCount('pendaftarans', 1);
    }

    /** @test */
    public function only_admin_or_staff_can_login_at_admin_box()
    {
        $this->seed(\Database\Seeders\UserAdminSeeder::class);

        // sukses admin
        $res1 = $this->post('/admin-login', ['email'=>'admin@pmb.test','password'=>'password123']);
        $res1->assertRedirect(route('admin.dashboard'));

        // gagal calon_mahasiswa
        $calon = User::factory()->create(['role'=>'calon_mahasiswa','password'=>bcrypt('pass12345')]);
        $res2 = $this->post('/admin-login', ['email'=>$calon->email,'password'=>'pass12345']);
        $res2->assertSessionHasErrors();
    }
}
