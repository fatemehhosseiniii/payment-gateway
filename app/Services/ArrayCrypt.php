<?php

namespace App\Services;

use Illuminate\Support\Facades\Crypt;

class ArrayCrypt
{

    public function encrypt(array $data): array
    {
        foreach ($data as $key => $value) {
            if (is_array($value)) {
                $data[$key] = $this->encrypt($value);
            } else {
                $data[$key] = Crypt::encryptString((string)$value);
            }
        }
        return $data;
    }

    function decrypt(array $data): array
    {
        foreach ($data as $key => $value) {
            if (is_array($value)) {
                $data[$key] = $this->decrypt($value);
            } else {
                $data[$key] = Crypt::decryptString($value);
            }
        }

        return $data;
    }

}
