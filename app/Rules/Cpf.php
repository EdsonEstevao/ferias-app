<?php

namespace App\Rules;


class Cpf
{
    public function __invoke($attribute,  $value, $fail): bool
    {
        $cpf = preg_replace('/[^0-9]/', '', $value);

        // dd($cpf);

        if (strlen($cpf) != 11 || preg_match('/(\d)\1{10}/', $cpf))  {
            flash()->error('O CPF informado é inválido.');
            return $fail('O CPF informado é inválido.');
        }


        for($t = 9;  $t < 11; $t++){
            for($d = 0, $c = 0; $c < $t; $c++){
                $d += $cpf[$c] * (($t + 1) - $c);
            }
            $d = ((10 * $d) % 11) % 10;
            flash()->error('O CPF informado é inválido.');
            if($cpf[$c] != $d) return false;
        }
        return true;
    }

    public function message(): string
    {
        return 'O CPF informado é inválido.';
    }
}
