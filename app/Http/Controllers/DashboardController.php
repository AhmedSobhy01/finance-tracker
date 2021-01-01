<?php

namespace App\Http\Controllers;

use App\Models\Due;
use App\Models\Cash;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $currency = ' ' . applicationSettings('currency');

        $total_transactions_d = Transaction::total();
        $total_cash_d = Cash::total();
        $total_lended_d = Due::where('type', 0)->sum('amount');
        $total_borrowed_d = Due::where('type', 1)->sum('amount');
        $total_all_d = $total_transactions_d + $total_lended_d - $total_borrowed_d;

        $total_transactions = number_format($total_transactions_d, 2) . $currency;
        $total_cash = number_format($total_cash_d, 2) . $currency;
        $total_lended = number_format($total_lended_d, 2) . $currency;
        $total_borrowed = number_format($total_borrowed_d, 2) . $currency;
        $total_all = number_format($total_all_d, 2) . $currency;

        $money_papers = DB::table('money_papers')->orderBy('amount', 'asc')->get();

        $recent_transactions = Transaction::latest()->limit(5)->get();
        $recent_dues = Due::latest()->limit(5)->get();
        $recent_cashes = Cash::latest()->limit(5)->get();

        $total_end = $total_transactions_d + (Due::where('type', 1)->whereNull('paid_at')->sum('amount') - Due::where('type', 0)->whereNull('paid_at')->sum('amount'));
        $total_cash_total_all = $total_end == $total_cash_d ? true : false;
        $diff_cash_all = $total_cash_d - $total_end;

        return view('dashboard', compact('total_transactions', 'total_cash', 'total_lended', 'total_borrowed', 'total_all', 'money_papers', 'recent_transactions', 'recent_dues', 'recent_cashes', 'diff_cash_all', 'total_cash_total_all'));
    }
}