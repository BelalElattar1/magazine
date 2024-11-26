<?php

namespace App;

trait ResponseTrait
{
    public function response($message, $status = 201, $data = []) {

        return response()->json([
            'Message' => $message,
            'data'    => $data
        ], $status);

    }
}
