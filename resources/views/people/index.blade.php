@extends('layouts.app')
@section('title', __('main.people'))

@section('content')
    <people-page :create-people-per="{{ auth()->user()->can('create people') ? 'true' : 'false' }}" :delete-people-per="{{ auth()->user()->can('delete people') ? 'true' : 'false' }}" add-person-url="{{ route('people.store') }}" :people-arr="{{ $data }}" delete-person-url="{{ route('people.destroy') }}"></people-page>
@endsection
