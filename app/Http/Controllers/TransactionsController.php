<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TransactionsController extends Controller
{
    public function index(Request $request)
    {
        $cat = $request->get('cat');

        if ($cat == 'income'){
            $transactions = Transaction::incomes()->latest()->paginate(applicationSettings('pagination_count'));
        }elseif ($cat == 'expense'){
            $transactions = Transaction::expenses()->latest()->paginate(applicationSettings('pagination_count'));
        } else{
            $transactions = Transaction::latest()->paginate(applicationSettings('pagination_count'));
        }

        return view('transcations.index', compact('transactions'));
    }

    public function store(Request $request)
    {
        if (!$request->wantsJson()) return abort(404);

        try {
            $validator = Validator::make($request->all(), [
                'type' => 'required|boolean',
                'amount' => 'required|numeric|min:0.01|max:1000000',
                'description' => 'nullable|string|max:100',
            ], [
                'type.required' => __('custom_validation.type.required'),
                'type.boolean' => __('custom_validation.type.required'),
                'amount.required' => __('custom_validation.amount.required'),
                'amount.numeric' => __('custom_validation.amount.numeric'),
                'amount.min' => __('custom_validation.amount.min:0.01'),
                'amount.max' => __('custom_validation.amount.max:1000000'),
                'description.string' => __('custom_validation.description.string'),
                'description.max' => __('custom_validation.description.max:100'),
            ]);
            if ($validator->fails()) return response_invalid_request(null, null, $validator->errors()->toArray());

            $transaction = Transaction::create([
                'process_serial' => generateProcessSerial(),
                'type' => $request->get('type'),
                'amount' => $request->get('amount'),
                'description' => $request->get('description'),
            ]);

            $data = $transaction->only(['id', 'process_serial', 'type', 'amount', 'description', 'created_at']);
            $data['type_raw'] = (int) $transaction->getRawOriginal('type');
            $data['created_at'] = $data['created_at']->format('Y-m-d h:i:s A');
            $data['view_url'] = route('process', ['serial' => $data['process_serial']]);

            return response_created("", "", $data);
        } catch (\Exception $e){
            return response_server_error();
        }
    }

    public function destroy(Request $request)
    {
        if (!$request->wantsJson()) return abort(404);

        try {
            $validator = Validator::make($request->all(), [
                'transactionId' => 'required|integer',
            ], [
                'transactionId.required' => __('custom_validation.transactionId.required'),
                'transactionId.integer' => __('custom_validation.transactionId.integer'),
            ]);
            if ($validator->fails()) return response_invalid_request(null, null, $validator->errors()->toArray());

            $transaction = Transaction::find($request->get('transactionId'));
            if (!$transaction) return response_not_found(__('messages.title.not_found'), __('messages.body.not_found'));

            $transaction->delete();
            return response_ok(__('messages.title.deleted'), __('messages.body.transaction_deleted_successfully'));
        } catch (\Exception $e){
            return response_server_error();
        }
    }
}