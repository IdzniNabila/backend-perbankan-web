<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TransferRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [

            'source_account' => [
                'required',
                'uuid'
            ],

            'destination_account' => [
                'required',
                'uuid',
                'different:source_account'
            ],

            'amount' => [
                'required',
                'numeric',
                'min:1000'
            ],

            'pin' => [
                'required',
                'digits:6'
            ]
        ];
    }
}