<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Due;
use App\Models\Person;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BorrowingsController extends Controller
{
    public function index(Request $request)
    {
        $cat = $request->get('cat');
        if ($cat == 'paid'){
            $borrowings = Due::borrowings()->whereNotNull('paid_at')->latest()->paginate(applicationSettings('pagination_count'));
        }elseif ($cat == 'unpaid'){
            $borrowings = Due::borrowings()->whereNull('paid_at')->latest()->paginate(applicationSettings('pagination_count'));
        }else{
            $borrowings = Due::borrowings()->latest()->paginate(applicationSettings('pagination_count'));
        }

        $people = Person::select('id', 'name')->latest()->get();
        return view('borrowings.index', compact('borrowings', 'people'));
    }

    public function update(Request $request)
    {
        if (!$request->wantsJson()) return abort(404);

        try {
            $validator = Validator::make($request->all(), [
                'dueId' => 'required|integer',
            ], [
                'dueId.required' => __('custom_validation.dueId.required'),
                'dueId.integer' => __('custom_validation.dueId.integer'),
            ]);
            if ($validator->fails()) return response_invalid_request(null, null, $validator->errors()->toArray());

            $due = Due::find($request->get('dueId'));
            if (!$due) return response_not_found(__('messages.title.not_found'), __('messages.body.not_found'));

            $current_state = $due->paid_at;

            $current_state ? $due->update(['paid_at' => null]) : $due->update(['paid_at' => Carbon::now()]);

            if($current_state) {
                $data = [
                    'id' => $due->id,
                    'paid' => $due->paid_at ? true : false,
                ];

                return response_ok(__('messages.title.deleted'), __('messages.body.borrowing_set_as_unpaid'), $data);
            }else {
                $data = [
                    'id' => $due->id,
                    'paid' => $due->paid_at ? true : false,
                    'paid_at' => $due->paid_at->format('Y-m-d h:i:s A'),
                ];

                return response_ok(__('messages.title.deleted'), __('messages.body.borrowing_set_as_paid'), $data);
            }
        } catch (\Exception $e){
            return response_server_error();
        }
    }

    public function store(Request $request)
    {
        if (!$request->wantsJson()) return abort(404);

        try {
            $validator = Validator::make($request->all(), [
                'personId' => 'required|integer|exists:people,id',
                'amount' => 'required|numeric|min:0.01|max:1000000',
                'description' => 'nullable|string|max:100',
                'created_at' => 'nullable|date'
            ], [
                'person.required' => __('custom_validation.person.required'),
                'person.integer' => __('custom_validation.person.not_found'),
                'amount.required' => __('custom_validation.amount.required'),
                'amount.numeric' => __('custom_validation.amount.numeric'),
                'amount.min' => __('custom_validation.amount.min:0.01'),
                'amount.max' => __('custom_validation.amount.max:1000000'),
                'description.string' => __('custom_validation.description.string'),
                'description.max' => __('custom_validation.description.max:100'),
            ]);
            if ($validator->fails()) return response_invalid_request(null, null, $validator->errors()->toArray());

            $request_data = [
                'process_serial' => generateProcessSerial(),
                'person_id' => $request->get('personId'),
                'type' => 1,
                'amount' => $request->get('amount'),
                'description' => $request->get('description'),
            ];
            $request->get('created_at') ? $request_data['created_at'] = $request->get('created_at') : false;

            $due = Due::create($request_data);
            $due->load('person');

            $data = $due->only(['id', 'process_serial', 'person', 'amount', 'description', 'created_at', 'paid_at']);
            $data['created_at'] = $data['created_at']->format('Y-m-d h:i:s A');
            $data['person_show_url'] = route('people.show', $due->person->id);
            $data['paid_url'] = route('process', ['serial' => $data['process_serial']]);
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
                'dueId' => 'required|integer',
            ], [
                'dueId.required' => __('custom_validation.dueId.required'),
                'dueId.integer' => __('custom_validation.dueId.integer'),
            ]);
            if ($validator->fails()) return response_invalid_request(null, null, $validator->errors()->toArray());

            $due = Due::find($request->get('dueId'));
            if (!$due) return response_not_found(__('messages.title.not_found'), __('messages.body.not_found'));

            $due->delete();
            return response_ok(__('messages.title.deleted'), __('messages.body.due_deleted_successfully'));
        } catch (\Exception $e){
            return response_server_error();
        }
    }
}