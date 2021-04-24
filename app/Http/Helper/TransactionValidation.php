<?php

namespace App\Http\Helper;

use Illuminate\Support\Facades\Validator;

class TransactionValidation
{

    public function transactionCallbackRequest(array $data): void
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

}
