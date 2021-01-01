<?php

return [
    'username' => [
        'required' => 'Username field is required.',
        'invalid' => 'Username is invalid.',
        'max:255' => 'Username can\'t be more than 255 characters.',
        'unique' => 'Username already exists.',
    ],
    'password' => [
        'required' => 'Password field is required.',
        'invalid' => 'Password is invalid.',
        'max:255' => 'Password can\'t be more than 255 characters.',
        'confirmed' => 'The password confirmation does not match.',
    ],
    'password_confirmation' => [
        'required' => 'Confirm Password field is required.',
        'invalid' => 'Confirm Password is invalid.',
        'max:255' => 'Confirm Password can\'t be more than 255 characters.',
        'confirmed' => 'The password confirmation does not match.',
    ],
    'timezone' => [
        'invalid' => 'Timezone is invalid'
    ],
    'name' => [
        'required' => 'Name field is required.',
        'invalid' => 'Name is invalid.',
        'max:255' => 'Name can\'t be more than 255 characters.',
        'unique' => 'This name is already registered.',
    ],
    'type' => [
        'required' => 'Type field is required.',
    ],
    'amount' => [
        'required' => 'Amount field is required.',
        'numeric' => 'Amount field must be numeric.',
        'min:0.01' => 'Amount field can\'t be less than 0.01.',
        'max:1000000' => 'Amount field can\'t be more than 1000000.',
        'invalid' => 'Amount is invalid.',
    ],
    'description' => [
        'required' => 'Description field is required.',
        'string' => 'Description must be a string.',
        'max:100' => 'Description can\'t be more than 100 characters.',
    ],
    'person' => [
        'required' => 'Person field is required.',
        'not_found' => 'Person wasn\'t found.',
    ],
    'transactionId' => [
        'required' => 'Transaction Id must be provided.',
        'invalid' => 'Transaction Id is invalid.',
    ],
    'cashId' => [
        'required' => 'Cash Id must be provided.',
        'integer' => 'Cash Id is invalid.',
    ],
    'personId' => [
        'required' => 'Person Id must be provided.',
        'integer' => 'Person Id is invalid.',
    ],
    'accountId' => [
        'required' => 'Account Id must be provided.',
        'integer' => 'Account Id is invalid.',
    ],
    'dueId' => [
        'required' => 'Due Id must be provided.',
        'integer' => 'Due Id is invalid.',
    ],
    'serial_number' => [
        'required' => 'Serial Number is required.',
        'numeric' => 'Serial Number must be numeric.',
        'invalid' => 'Serial Number is invalid.',
    ],
];