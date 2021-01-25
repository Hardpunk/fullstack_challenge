<?php

namespace App\Http\Requests\API;

use App\Models\User;
use Illuminate\Contracts\Validation\Validator;
use InfyOm\Generator\Request\APIRequest;
use Response;

class CreateUserAPIRequest extends APIRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = User::$rules;
        $rules['password'] = ['required', 'string', 'min:6', 'confirmed'];

        return $rules;
    }
}
