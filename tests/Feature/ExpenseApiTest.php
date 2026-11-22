<?php

namespace Tests\Feature;

use App\Models\Expense;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExpenseApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_page_loads(): void
    {
        $this->get('/')->assertOk()->assertSee('Add expense');
    }

    public function test_create_list_update_delete(): void
    {
        $id = $this->postJson('/api/expenses', [
            'amount' => 2500, 'category' => 'Farm Input', 'date' => '2026-11-08', 'note' => 'Fertilizer',
        ])->assertCreated()->assertJsonPath('category', 'Farm Input')->json('id');

        $this->getJson('/api/expenses')->assertOk()->assertJsonCount(1)
            ->assertJsonPath('0.amount', 2500)->assertJsonPath('0.date', '2026-11-08');

        $this->putJson("/api/expenses/$id", [
            'amount' => 2750.5, 'category' => 'Farm Input', 'date' => '2026-11-09', 'note' => 'Fertilizer + delivery',
        ])->assertOk()->assertJsonPath('amount', 2750.5);

        $this->deleteJson("/api/expenses/$id")->assertNoContent();
        $this->assertSame(0, Expense::count());
    }

    public function test_validation(): void
    {
        $this->postJson('/api/expenses', ['amount' => 0, 'category' => 'Nope', 'date' => '22/11/2026'])
            ->assertUnprocessable()->assertJsonValidationErrors(['amount', 'category', 'date']);
    }

    public function test_currency_setting(): void
    {
        $this->getJson('/api/settings')->assertJson(['currency' => 'PHP']);
        $this->putJson('/api/settings', ['currency' => 'USD'])->assertOk();
        $this->getJson('/api/settings')->assertJson(['currency' => 'USD']);
        $this->putJson('/api/settings', ['currency' => 'XYZ'])->assertUnprocessable();
    }
}
