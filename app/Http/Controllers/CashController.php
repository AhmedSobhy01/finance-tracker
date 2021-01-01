<?php

namespace App\Http\Controllers;

use App\Models\Cash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;

class CashController extends Controller
{
    public function index(Request $request)
    {
        $cat = $request->get('cat');
        if ($cat == 'deleted'){
            $cashes = Cash::onlyTrashed()->latest()->get();
        }else if ($cat == 'all'){
            $cashes = Cash::withTrashed()->latest()->get();
        }else{
            $cashes = Cash::orderBy('amount', 'desc')->get();
        }

        $money_papers = DB::table('money_papers')->orderBy('amount', 'asc')->get();

        return view('cash.index', compact('cashes', 'money_papers'));
    }


    public function store(Request $request)
    {
        if (!$request->wantsJson()) return abort(404);

        try {
            $validator = Validator::make($request->all(), [
                'amount' => ['required', 'numeric', Rule::in(['0.5', '1', '5', '10', '20', '50', '100', '200'])],
                'serial_number' => 'required|integer|min:1000000|max:9999999',
                'description' => 'nullable|string|max:100',
            ], [
                'amount.required' => __('custom_validation.amount.required'),
                'amount.numeric' => __('custom_validation.amount.invalid'),
                'amount.min' => __('custom_validation.amount.invalid'),
                'amount.max' => __('custom_validation.amount.invalid'),
                'serial_number.required' => __('custom_validation.serial_number.required'),
                'serial_number.integer' => __('custom_validation.serial_number.invalid'),
                'serial_number.min' => __('custom_validation.serial_number.invalid'),
                'serial_number.max' => __('custom_validation.serial_number.invalid'),
                'description.string' => __('custom_validation.description.string'),
                'description.max' => __('custom_validation.description.max:100'),
            ]);
            if ($validator->fails()) return response_invalid_request(null, null, $validator->errors()->toArray());

            $cash = Cash::create([
                'process_serial' => generateProcessSerial(),
                'amount' => $request->get('amount'),
                'serial_number' => $request->get('serial_number'),
                'description' => $request->get('description'),
            ]);

            $data = $cash->only(['id', 'process_serial', 'amount', 'serial_number', 'description', 'created_at']);
            $data['created_at'] = $data['created_at']->diffForHumans();
            $data['view_url'] = route('process', ['serial' => $data['process_serial']]);

            return response_created("", "", $data);
        } catch (\Exception $e){
            return response_server_error();
        }
    }


    public function update(Request $request)
    {
        if (!$request->wantsJson()) return abort(404);

        try {
            $validator = Validator::make($request->all(), [
                'cashId' => 'required|integer',
            ], [
                'cashId.required' => __('custom_validation.cashId.required'),
                'cashId.integer' => __('custom_validation.cashId.invalid'),
            ]);
            if ($validator->fails()) return response_invalid_request(null, null, $validator->errors()->toArray());

            $cash = Cash::withTrashed()->find($request->get('cashId'));
            if (!$cash) return response_not_found(__('messages.title.not_found'), __('messages.body.not_found'));

            if($cash->trashed()){
                $cash->restore();

                $data = [
                    'id' => $cash->id,
                    'available' => $cash->trashed() ? false : true,
                ];

                return response_ok(__('messages.title.deleted'), __('messages.body.cash_restored_successfully'), $data);
            }else{
                $cash->delete();

                $data = [
                    'id' => $cash->id,
                    'available' => $cash->trashed() ? false : true,
                    'deleted_at' => $cash->deleted_at->format('Y-m-d h:i:s A'),
                ];

                return response_ok(__('messages.title.deleted'), __('messages.body.cash_deleted_successfully'), $data);
            }
        } catch (\Exception $e){
            return response_server_error();
        }
    }
}