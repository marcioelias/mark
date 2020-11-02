<?php

namespace App\Rules;

use App\Custom\ValidaCpfCnpj;
use Illuminate\Contracts\Validation\Rule;


class cpfCnpj implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $validaCpfCnpf = new ValidaCpfCnpj;
        if (strlen($value) == 14) {
            return $validaCpfCnpf->valida_cpf($value);
        } else {
            return $validaCpfCnpf->valida_cnpj($value);
        }
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Número de documento inválido.';
    }
}
