<?php

namespace App\Http\Requests;

use HttpResponseException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;

class PermissionFormRequest extends FormRequest
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
        return [
            'source_role_id' => 'required',
            'target_role_id' => 'required',
            '*_role_id' => 'distinct|exists:App\Role,id',
            'permission_key' => 'required',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        $answer = [
            'status' => false,
            'errors' => $validator->errors(),
        ];
        $response = new JsonResponse( $answer, Response::HTTP_BAD_REQUEST );
        throw (new ValidationException($validator, $response));
    }
}
