<?php

namespace App\Rules;

use App\Utilities\Inspections\Spam;
use Illuminate\Contracts\Validation\Rule;

class SpamFree implements Rule  //Добавляется через artisan make:rule SpamFree и в AppServiceProvider
{
    /**
     * @var Spam
     */
    protected $spam;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct(Spam $spam)
    {
        $this->spam = $spam;
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
        try {
            return !$this->spam->detect($value);
        } catch(\Exception $e){
            return false;
        }

    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Ваше сообщение распознано, как спам.';
    }
}
