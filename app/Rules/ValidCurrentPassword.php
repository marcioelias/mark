<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Hash;

class ValidCurrentPassword implements Rule
{

    protected $currentPassword = null;
    protected $validate = false;
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($currentPassword, $validate)
    {
        $this->currentPassword = $currentPassword;
        $this->validate = $validate;
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
        if ($this->validate) {
            return (Hash::check($value, $this->currentPassword));
        } else {
            return true;
        }
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'A senha atual não confere.';
    }
}
