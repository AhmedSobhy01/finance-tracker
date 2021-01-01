<?php

namespace App\Http\Controllers;

use App\Models\Cash;
use App\Models\Due;
use App\Models\Transaction;
use Illuminate\Http\Request;

class ProcessController extends Controller
{
    public function index(Request $request)
    {
        $serial = $request->serial;

        if ($serial == "") return redirect()->back();

        if ($transaction = Transaction::where('process_serial', $serial)->limit(1)->get()){
            if (count($transaction)) {
                $process = $transaction;
                $table_type = 1;
            }
        }
        if ($due = Due::where('process_serial', $serial)->limit(1)->get()) {
            if (count($due)) {
                $process = $due;
                $table_type = 2;
            }
        }
        if ($cash = Cash::withTrashed()->where('process_serial', $serial)->limit(1)->get()) {
            if (count($cash)) {
                $process = $cash;
                $table_type = 3;
            }
        }

        $process = $process[0] ?? null;
        if (!$process) $table_type = 0;

        return view('process.index', compact('process', 'table_type'));
    }
}