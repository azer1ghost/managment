<?php

namespace App\Http\Callbacks;

class Notify
{
    public function success($data = 'Record'): array
    {
        return  [
            'notify' =>
                [
                    'title' => 'Successfully!',
                    'type' => 'green',
                    'message' => "<b> $data </b> processed successfully"
                ]
        ];
    }

    public function info($data =  'Record'): array
    {
        return  [
            'notify' =>
                [
                    'title' => 'Successfully!',
                    'type' => 'blue',
                    'message' => "<b> $data </b> processed successfully"
                ]
        ];
    }

    public function error(): array
    {
        return  [
            'notify' =>
                [
                    'title' => 'Ops... !',
                    'type' => 'red',
                    'message' => "Something went wrong."
                ]
        ];
    }

}