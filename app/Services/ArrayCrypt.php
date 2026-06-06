<?php

namespace App\Services;

use Illuminate\Support\Facades\Crypt;

class ArrayCrypt
{

    public function __construct(public array $params)
    {
        //
    }

    public function encrypt(): array
    {
        $data = [];
        foreach ($this->params as $key => $value) {
            if (is_array($value)) {
                $data[$key] = $this->encrypt($value);
            } else {
                $data[$key] = Crypt::encryptString((string)$value);
            }
        }
        return $data;
    }

    function decryptValues(): array
    {
        $data = [];
        foreach ($this->params as $key => $value) {
            if (is_array($value)) {
                $data[$key] = $this->decryptValues($value);
            } else {
                $data[$key] = Crypt::decryptString($value);
            }
        }

        return $data;
    }

}
