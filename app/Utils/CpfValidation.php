<?php

declare(strict_types = 1);

namespace App\Utils;

class CpfValidation
{
    public function validate($attribute, $value, $parameters, $validator)
    {
        return $this->isValidate($attribute, $value);
    }

    protected function isValidate($attribute, $value)
    {
        $c = preg_replace('/\D/', '', $value);

        if (strlen($c) != 11 || preg_match("/^{$c[0]}{11}$/", $c)) {
            return false;
        }

        for ($s = 10, $n = 0, $i = 0; $s >= 2; $n += (int) $c[$i++] * $s--);

        if ((int) $c[9] != ((($n %= 11) < 2) ? 0 : 11 - $n)) {
            return false;
        }

        for ($s = 11, $n = 0, $i = 0; $s >= 2; $n += (int) $c[$i++] * $s--);

        if ((int) $c[10] != ((($n %= 11) < 2) ? 0 : 11 - $n)) {
            return false;
        }

        return true;
    }
}
