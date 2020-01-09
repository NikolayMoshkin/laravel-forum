<?php

namespace App\Http\Forms;

use App\Reply;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class CreatePostForm extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
//        $this->authorize('create', Reply::class);
//        Вместо authorize используем Gate:
        return Gate::allows('create', Reply::class);  //проверка на спам сообщениями
//        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */

    protected function failedAuthorization()
    {
        return back()->withErrors(['error'=> 'Вы спамите сообщениями']);
    }

    public function rules()
    {
        return [
            'body'=>'required|min:2|spamfree'
        ];
    }
}
