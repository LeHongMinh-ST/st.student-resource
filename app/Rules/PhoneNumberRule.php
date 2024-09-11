<?php

declare(strict_types=1);

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class PhoneNumberRule implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct() {}

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     */
    public function passes($attribute, $value): bool
    {
        if (! $value) {
            return true;
        }

        return (bool) (preg_match('/^(0|\+84|84)?(3[2-9]|5[2689]|7[06-9]|8[1-689]|9[0-46-9])[0-9]{7}$/', $value));
    }

    /**
     * Get the validation error message.
     */
    public function message(): string
    {
        return 'Số điện thoại không đúng định dạng';
    }
}
