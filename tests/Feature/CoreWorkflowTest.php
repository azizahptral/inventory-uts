<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Product;
use App\Models\Supplier;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CoreWorkflowTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_page_loads(): void
    {
        $response = $this->get('/login');
        $response->assertStatus(200);
        $response->assertSee('Diesel Parts');
    }

    public function test_dashboard_loads_for_admin_and_staff(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $responseAdmin = $this->actingAs($admin)->get('/dashboard');
        $responseAdmin->assertStatus(200);
        $responseAdmin->assertSee('Kelola Pengguna');

        $staff = User::factory()->create(['role' => 'staff']);
        $responseStaff = $this->actingAs($staff)->get('/dashboard');
        $responseStaff->assertStatus(200);
        $responseStaff->assertDontSee('Kelola Pengguna');
    }

    public function test_products_page_loads(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $response = $this->actingAs($admin)->get('/products');
        $response->assertStatus(200);
    }

    public function test_suppliers_page_loads(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $response = $this->actingAs($admin)->get('/suppliers');
        $response->assertStatus(200);
    }

    public function test_purchases_page_loads(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $response = $this->actingAs($admin)->get('/purchases');
        $response->assertStatus(200);
    }

    public function test_sales_page_loads(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $response = $this->actingAs($admin)->get('/sales');
        $response->assertStatus(200);
    }
}

