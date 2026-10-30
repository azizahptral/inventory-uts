<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CategoryAndUserTest extends TestCase
{
    use RefreshDatabase;

    /* ---------------------------------------------------------
     * TESTS: KATEGORI
     * --------------------------------------------------------- */

    public function test_guest_cannot_access_categories(): void
    {
        $response = $this->get('/categories');
        $response->assertRedirect('/login');
    }

    public function test_staff_can_view_categories_and_search(): void
    {
        $staff = User::factory()->create(['role' => 'staff']);
        Category::create(['name' => 'Filter Solar', 'code' => 'FLT', 'description' => 'Saringan']);
        Category::create(['name' => 'Oli Mesin', 'code' => 'OIL', 'description' => 'Pelumas']);

        $response = $this->actingAs($staff)->get('/categories?search=Filter');
        $response->assertStatus(200);
        $response->assertSee('Filter Solar');
        $response->assertDontSee('Oli Mesin');
        $response->assertDontSee('+ Tambah Kategori Baru'); // Staff tidak melihat tombol tambah
    }

    public function test_staff_cannot_create_or_modify_categories(): void
    {
        $staff = User::factory()->create(['role' => 'staff']);
        $cat = Category::create(['name' => 'Filter Solar', 'code' => 'FLT']);

        // Staff tidak bisa akses form create
        $this->actingAs($staff)->get('/categories/create')->assertStatus(403);

        // Staff tidak bisa store
        $this->actingAs($staff)->post('/categories', [
            'name' => 'Kategori Baru',
        ])->assertStatus(403);

        // Staff tidak bisa akses edit
        $this->actingAs($staff)->get("/categories/{$cat->id}/edit")->assertStatus(403);

        // Staff tidak bisa update
        $this->actingAs($staff)->put("/categories/{$cat->id}", [
            'name' => 'Kategori Diubah',
        ])->assertStatus(403);

        // Staff tidak bisa delete
        $this->actingAs($staff)->delete("/categories/{$cat->id}")->assertStatus(403);
    }

    public function test_admin_can_crud_category(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        // Form create
        $this->actingAs($admin)->get('/categories/create')->assertStatus(200);

        // Store
        $storeResponse = $this->actingAs($admin)->post('/categories', [
            'name' => 'Sistem Pengereman',
            'code' => 'BRK',
            'description' => 'Kampas rem dan piringan',
        ]);
        $storeResponse->assertRedirect('/categories');
        $this->assertDatabaseHas('categories', ['name' => 'Sistem Pengereman', 'code' => 'BRK']);

        $category = Category::where('code', 'BRK')->first();

        // Edit
        $this->actingAs($admin)->get("/categories/{$category->id}/edit")->assertStatus(200);

        // Update
        $updateResponse = $this->actingAs($admin)->put("/categories/{$category->id}", [
            'name' => 'Sistem Rem & Cakram',
            'code' => 'BRK',
            'description' => 'Deskripsi baru',
        ]);
        $updateResponse->assertRedirect('/categories');
        $this->assertDatabaseHas('categories', ['name' => 'Sistem Rem & Cakram']);

        // Delete
        $deleteResponse = $this->actingAs($admin)->delete("/categories/{$category->id}");
        $deleteResponse->assertRedirect('/categories');
        $this->assertDatabaseMissing('categories', ['id' => $category->id]);
    }

    public function test_product_input_automatically_associates_and_creates_category(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        // Input produk dengan kategori baru 'Pelumas & Oli'
        $response = $this->actingAs($admin)->post('/products', [
            'sku' => 'OIL-001',
            'name' => 'Oli Mesin Diesel HD40',
            'category_name' => 'Pelumas & Oli',
            'purchase_price' => 50000,
            'selling_price' => 75000,
            'stock' => 20,
            'min_stock' => 5,
        ]);

        $response->assertRedirect('/products');

        // Pastikan kategori otomatis terbuat di database
        $this->assertDatabaseHas('categories', ['name' => 'Pelumas & Oli']);
        $category = Category::where('name', 'Pelumas & Oli')->first();

        // Pastikan produk terhubung dengan kategori tersebut
        $this->assertDatabaseHas('products', [
            'sku' => 'OIL-001',
            'category_id' => $category->id,
        ]);

        // Halaman produk menampilkan nama kategori
        $indexResponse = $this->actingAs($admin)->get('/products');
        $indexResponse->assertSee('Pelumas & Oli');
    }

    /* ---------------------------------------------------------
     * TESTS: KELOLA PENGGUNA (USER MANAGEMENT)
     * --------------------------------------------------------- */

    public function test_guest_cannot_access_users(): void
    {
        $response = $this->get('/users');
        $response->assertRedirect('/login');
    }

    public function test_staff_cannot_access_or_manage_users(): void
    {
        $staff = User::factory()->create(['role' => 'staff']);
        $targetUser = User::factory()->create(['role' => 'staff']);

        // Index
        $this->actingAs($staff)->get('/users')->assertStatus(403);

        // Create
        $this->actingAs($staff)->get('/users/create')->assertStatus(403);

        // Store
        $this->actingAs($staff)->post('/users', [
            'name' => 'Staff Baru',
            'email' => 'staffbaru@dieselparts.test',
            'role' => 'staff',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ])->assertStatus(403);

        // Edit
        $this->actingAs($staff)->get("/users/{$targetUser->id}/edit")->assertStatus(403);

        // Update
        $this->actingAs($staff)->put("/users/{$targetUser->id}", [
            'name' => 'Nama Baru',
            'email' => $targetUser->email,
            'role' => 'staff',
        ])->assertStatus(403);

        // Destroy
        $this->actingAs($staff)->delete("/users/{$targetUser->id}")->assertStatus(403);
    }

    public function test_admin_can_crud_users(): void
    {
        $admin = User::factory()->create(['role' => 'admin', 'email' => 'superadmin@dieselparts.test']);

        // Index
        $indexResponse = $this->actingAs($admin)->get('/users');
        $indexResponse->assertStatus(200);
        $indexResponse->assertSee('Kelola Akun Pengguna');

        // Create Form
        $this->actingAs($admin)->get('/users/create')->assertStatus(200);

        // Store
        $storeResponse = $this->actingAs($admin)->post('/users', [
            'name' => 'Kasir Baru',
            'email' => 'kasirbaru@dieselparts.test',
            'role' => 'staff',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);
        $storeResponse->assertRedirect('/users');
        $this->assertDatabaseHas('users', ['email' => 'kasirbaru@dieselparts.test', 'role' => 'staff']);

        $createdUser = User::where('email', 'kasirbaru@dieselparts.test')->first();

        // Edit Form
        $this->actingAs($admin)->get("/users/{$createdUser->id}/edit")->assertStatus(200);

        // Update
        $updateResponse = $this->actingAs($admin)->put("/users/{$createdUser->id}", [
            'name' => 'Kasir Senior',
            'email' => 'kasirsenior@dieselparts.test',
            'role' => 'staff',
        ]);
        $updateResponse->assertRedirect('/users');
        $this->assertDatabaseHas('users', ['email' => 'kasirsenior@dieselparts.test', 'name' => 'Kasir Senior']);

        // Admin cannot delete self
        $deleteSelfResponse = $this->actingAs($admin)->delete("/users/{$admin->id}");
        $this->assertDatabaseHas('users', ['id' => $admin->id]);

        // Admin can delete target user
        $deleteResponse = $this->actingAs($admin)->delete("/users/{$createdUser->id}");
        $deleteResponse->assertRedirect('/users');
        $this->assertDatabaseMissing('users', ['id' => $createdUser->id]);
    }
}

