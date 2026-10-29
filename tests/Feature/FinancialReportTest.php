<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FinancialReportTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_access_financial_reports(): void
    {
        $response = $this->get('/financial-reports');
        $response->assertRedirect('/login');
    }

    public function test_staff_is_forbidden_from_financial_reports(): void
    {
        $staff = User::factory()->create(['role' => 'staff']);

        $response = $this->actingAs($staff)->get('/financial-reports');
        $response->assertStatus(403);
    }

    public function test_staff_is_forbidden_from_financial_reports_print(): void
    {
        $staff = User::factory()->create(['role' => 'staff']);

        $response = $this->actingAs($staff)->get('/financial-reports/print');
        $response->assertStatus(403);
    }

    public function test_admin_can_access_financial_reports(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)->get('/financial-reports');
        $response->assertStatus(200);
        $response->assertSee('Laporan Keuangan');
        $response->assertSee('Total Pendapatan');
    }

    public function test_admin_can_access_financial_reports_with_period_filters(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        // Daily
        $this->actingAs($admin)
            ->get('/financial-reports?period=daily&date=2026-10-28')
            ->assertStatus(200);

        // Weekly
        $this->actingAs($admin)
            ->get('/financial-reports?period=weekly&week_date=2026-10-28')
            ->assertStatus(200);

        // Monthly
        $this->actingAs($admin)
            ->get('/financial-reports?period=monthly&month=10&year=2026')
            ->assertStatus(200);

        // Yearly
        $this->actingAs($admin)
            ->get('/financial-reports?period=yearly&year=2026')
            ->assertStatus(200);

        // Custom
        $this->actingAs($admin)
            ->get('/financial-reports?period=custom&start_date=2026-10-01&end_date=2026-10-31')
            ->assertStatus(200);
    }

    public function test_admin_can_access_print_view(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)->get('/financial-reports/print?period=monthly&month=10&year=2026');
        $response->assertStatus(200);
        $response->assertSee('LAPORAN KEUANGAN RESMI');
    }
}
