<?php

namespace App\Models;

use App\Traits\TimezonesTrait;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use TimezonesTrait;

    protected $fillable = ['process_serial', 'type', 'amount', 'description'];

    public function getTypeAttribute($value)
    {
        return $value == 0 ? 'Expense' : 'Income';
    }

    public function getAmountAttribute($value)
    {
        return number_format($value, 2) . ' ' . applicationSettings('currency');
    }

    public function getDescriptionAttribute($value)
    {
        return $value ?? "-";
    }

    public function scopeIncomes()
    {
        return $this->where('type', 1);
    }

    public function scopeExpenses()
    {
        return $this->where('type', 0);
    }

    public function scopeTotalIncomes()
    {
        return $this->select(DB::raw('SUM(CASE WHEN type = 1 THEN amount ELSE 0 END) as incomes'))->get()[0]->incomes ?? 0;
    }

    public function scopeTotalExpenses()
    {
        return $this->select(DB::raw('SUM(CASE WHEN type = 0 THEN amount ELSE 0 END) as expenses'))->get()[0]->expenses ?? 0;
    }

    public function scopeTotal()
    {
        return $this->select(DB::raw('(SUM(CASE WHEN type = 1 THEN amount ELSE 0 END) - SUM(CASE WHEN type = 0 THEN amount ELSE 0 END)) as total'))->get()[0]->total ?? 0;
    }
}