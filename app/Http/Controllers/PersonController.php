<?php

namespace App\Http\Controllers;

use App\Models\Person;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class PersonController extends Controller
{
    public function index()
    {
        $people = Person::select('id', 'name', 'created_at')->latest()->get();

        $data = $people->map(function ($person) {
            return [
                'id' => $person->id,
                'name' => $person->name,
                'created_at' => $person->created_at->format("Y-m-d h:i:s A"),
                'show_url' => route('people.show', $person['id'])
            ];
        });

        return view('people.index', compact('data'));
    }

    public function show(Person $person)
    {
        $person->load('dues');

        $total_borrowed = ($person->dues()->select(DB::raw('SUM(CASE WHEN type = 0 THEN amount ELSE 0 END) as borrowed'))->get()[0]->borrowed ?? 0);
        $total_lended = ($person->dues()->select(DB::raw('SUM(CASE WHEN type = 1 THEN amount ELSE 0 END) as lended'))->get()[0]->lended ?? 0);
        $total_unpaid_borrowed = ($person->dues()->select(DB::raw('SUM(CASE WHEN type = 0 THEN amount ELSE 0 END) as borrowed'))->whereNull('paid_at')->get()[0]->borrowed ?? 0);
        $total_unpaid_lended = ($person->dues()->select(DB::raw('SUM(CASE WHEN type = 1 THEN amount ELSE 0 END) as lended'))->whereNull('paid_at')->get()[0]->lended ?? 0);
        $total_unpaid_borrowed_lended = $total_unpaid_borrowed - $total_unpaid_lended;
        $total_paid_borrowed = $total_borrowed - $total_unpaid_borrowed;
        $total_paid_lended = $total_lended - $total_unpaid_lended;
        $total_borrowed = number_format($total_borrowed, 2) . ' ' . applicationSettings('currency');
        $total_lended = number_format($total_lended, 2) . ' ' . applicationSettings('currency');
        $total_unpaid_borrowed = number_format($total_unpaid_borrowed, 2) . ' ' . applicationSettings('currency');
        $total_unpaid_lended = number_format($total_unpaid_lended, 2) . ' ' . applicationSettings('currency');
        $total_unpaid_borrowed_lended = number_format($total_unpaid_borrowed_lended, 2) . ' ' . applicationSettings('currency');
        $total_paid_borrowed = number_format($total_paid_borrowed, 2) . ' ' . applicationSettings('currency');
        $total_paid_lended = number_format($total_paid_lended, 2) . ' ' . applicationSettings('currency');

        return view('people.show', compact('person', 'total_borrowed', 'total_lended', 'total_unpaid_borrowed', 'total_unpaid_lended', 'total_unpaid_borrowed_lended', 'total_paid_borrowed', 'total_paid_lended'));
    }

    public function store(Request $request)
    {
        if (!$request->wantsJson()) return abort(404);

        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255|unique:people,name',
            ], [
                'name.required' => __('custom_validation.name.required'),
                'name.string' => __('custom_validation.name.invalid'),
                'name.max' => __('custom_validation.name.max:255'),
                'name.unique' => __('custom_validation.name.unique'),
            ]);
            if ($validator->fails()) return response_invalid_request(null, null, $validator->errors()->toArray());

            $transaction = Person::create([
                'name' => $request->get('name'),
            ]);

            $data = $transaction->only(['id', 'name', 'created_at']);
            $data['created_at'] = $data['created_at']->format('Y-m-d h:i:s A');
            $data['view_url'] = route('people.show', $data['id']);

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
                'personId' => 'required|integer',
            ], [
                'personId.required' => __('custom_validation.personId.required'),
                'personId.integer' => __('custom_validation.personId.invalid'),
            ]);
            if ($validator->fails()) return response_invalid_request(null, null, $validator->errors()->toArray());

            $person = Person::find($request->get('personId'));
            if (!$person) return response_not_found(__('messages.title.not_found'), __('messages.body.not_found'));

            $person->delete();
            return response_ok(__('messages.title.deleted'), __('messages.body.person_deleted_successfully'));
        } catch (\Exception $e){
            return response_server_error();
        }
    }
}
