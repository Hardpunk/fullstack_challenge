<?php

namespace App\Http\Requests\API;

use App\Models\User;
use InfyOm\Generator\Request\APIRequest;

class UpdateUserAPIRequest extends APIRequest
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
        $rules['password'] = ['sometimes', 'required', 'min:6', 'confirmed'];

        return $rules;
    }

    /**
     * Force response to be in JSON format
     *
     * @return bool
     */
    public function expectsJson()
    {
        return true;
    }
}
