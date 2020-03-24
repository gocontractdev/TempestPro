<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;

class TestFormRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'user_id' => 'required|exists:App\User,id',
            'key' => 'required',
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
