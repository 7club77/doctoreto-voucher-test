<?php

namespace App\Http\Helper;

use Illuminate\Support\Facades\Validator;

class WalletValidation
{

    public function depositCallbackRequest(array $data): void
    {

        $validator = Validator::make($data, [
            'code' => [
                'string',
                'size:9',
            ],
            'transaction_id' => [
                'required',
                'uuid',
            ],
        ],
            $messages = [
                'required' => ' :attribute اجباری است',
                'string' => ':attribute باید از نوع رشته باشد',
                'size' => 'اشتباه است :attribute طول',

            ]
        );

        if ($validator->fails()) {
            throw new \RuntimeException($validator->errors()->all()[0]);
        }
    }

    public function depositRequest(array $data): void
    {

        $validator = Validator::make($data, [
            'code' => [
                'string',
                'size:9',
            ],
            'amount' => [
                'required',
                'integer',
            ],
        ],
            $messages = [
                'required' => ' :attribute اجباری است',
                'string' => ':attribute باید از نوع رشته باشد',
                'size' => 'اشتباه است :attribute طول',

            ]
        );

        if ($validator->fails()) {
            throw new \RuntimeException($validator->errors()->all()[0]);
        }
    }
}
