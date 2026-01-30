<?php

namespace App\Livewire\Finance;

use App\Models\Expense;
use App\Models\ExpenseCategory;
use Livewire\Component;
use Livewire\WithPagination;
use Mary\Traits\Toast;
use Carbon\Carbon;

class Expenses extends Component
{
    use WithPagination, Toast;

    public bool $expenseModal = false;
    public bool $categoryModal = false;
    
    // Expense Form
    public $expense_id;
    public $expense_category_id;
    public $title;
    public $amount;
    public $expense_date;
    public $description;

    // Category Form
    public $category_name;
    public $category_description;

    // Filters
    public $search = '';
    public $filter_category = '';
    public $filter_month;
    public $filter_year;

    public function mount()
    {
        $this->expense_date = date('Y-m-d');
        $this->filter_month = date('m');
        $this->filter_year = date('Y');
    }

    public function createExpense()
    {
        $this->reset(['expense_id', 'expense_category_id', 'title', 'amount', 'description']);
        $this->expense_date = date('Y-m-d');
        $this->expenseModal = true;
    }

    public function editExpense(Expense $expense)
    {
        $this->expense_id = $expense->id;
        $this->expense_category_id = $expense->expense_category_id;
        $this->title = $expense->title;
        $this->amount = $expense->amount;
        $this->expense_date = $expense->expense_date->format('Y-m-d');
        $this->description = $expense->description;
        $this->expenseModal = true;
    }

    public function saveExpense()
    {
        $this->validate([
            'expense_category_id' => 'required|exists:expense_categories,id',
            'title' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'expense_date' => 'required|date',
        ]);

        Expense::updateOrCreate(
            ['id' => $this->expense_id],
            [
                'expense_category_id' => $this->expense_category_id,
                'title' => $this->title,
                'amount' => $this->amount,
                'expense_date' => $this->expense_date,
                'description' => $this->description,
            ]
        );

        $this->success('Data pengeluaran berhasil disimpan');
        $this->expenseModal = false;
    }

    public function deleteExpense(Expense $expense)
    {
        $expense->delete();
        $this->success('Pengeluaran berhasil dihapus');
    }

    // Category Management
    public function saveCategory()
    {
        $this->validate([
            'category_name' => 'required|string|max:255',
        ]);

        ExpenseCategory::create([
            'name' => $this->category_name,
            'description' => $this->category_description,
        ]);

        $this->reset(['category_name', 'category_description']);
        $this->success('Kategori berhasil ditambahkan');
        $this->categoryModal = false;
    }

    public function render()
    {
        $query = Expense::with('category')
            ->when($this->search, fn($q) => $q->where('title', 'like', '%' . $this->search . '%'))
            ->when($this->filter_category, fn($q) => $q->where('expense_category_id', $this->filter_category))
            ->when($this->filter_month, fn($q) => $q->whereMonth('expense_date', $this->filter_month))
            ->when($this->filter_year, fn($q) => $q->whereYear('expense_date', $this->filter_year))
            ->orderBy('expense_date', 'desc');

        return view('livewire.finance.expenses', [
            'expenses' => $query->paginate(10),
            'categories' => ExpenseCategory::all(),
            'totalAmount' => $query->sum('amount')
        ]);
    }
}
