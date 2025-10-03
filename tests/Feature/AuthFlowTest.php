<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthFlowTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function admin_can_login_via_admin_box_and_redirect_to_admin_dashboard()
    {
        $admin = User::factory()->create([
            'email' => 'admin@pmb.test',
            'password' => Hash::make('password123'),
            'role' => 'admin',
        ]);

        $res = $this->post('/admin-login', ['email'=>$admin->email, 'password'=>'password123']);
        $res->assertRedirect(route('admin.dashboard'));
    }

    /** @test */
    public function calon_can_login_via_calon_box_and_redirect_to_calon_dashboard()
    {
        $calon = User::factory()->create([
            'email' => 'calon@pmb.test',
            'password' => Hash::make('password123'),
            'role' => 'calon_mahasiswa',
        ]);

        $res = $this->post('/calon-login', ['email'=>$calon->email, 'password'=>'password123']);
        $res->assertRedirect(route('pendaftaran.dashboard'));
    }

    /** @test */
    public function calon_cannot_use_admin_login_box()
    {
        $calon = User::factory()->create([
            'email' => 'calon@pmb.test',
            'password' => Hash::make('password123'),
            'role' => 'calon_mahasiswa',
        ]);

        $res = $this->post('/admin-login', ['email'=>$calon->email, 'password'=>'password123']);
        $res->assertSessionHasErrors();
    }
}
