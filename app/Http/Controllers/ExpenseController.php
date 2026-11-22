<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ExpenseController extends Controller
{
    public function index()
    {
        return Expense::orderByDesc('date')->orderByDesc('id')->get();
    }

    public function store(Request $request)
    {
        return response()->json(Expense::create($this->validated($request)), 201);
    }

    public function update(Request $request, Expense $expense)
    {
        $expense->update($this->validated($request));

        return $expense;
    }

    public function destroy(Expense $expense)
    {
        $expense->delete();

        return response()->noContent();
    }

    private function validated(Request $request): array
    {
        $data = $request->validate([
            'amount' => ['required', 'numeric', 'min:0.01', 'max:9999999999'],
            'category' => ['required', Rule::in(Expense::CATEGORIES)],
            'date' => ['required', 'date_format:Y-m-d'],
            'note' => ['nullable', 'string', 'max:1000'],
        ]);
        $data['amount'] = round((float) $data['amount'], 2);
        $data['note'] = trim($data['note'] ?? '');

        return $data;
    }
}
