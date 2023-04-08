<?php


namespace App\Http\Requests;


use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\JsonResponse;

abstract class ApiRequest extends FormRequest
{
        abstract public  function authorize();

        abstract public function rules();

    protected function failedValidation(Validator $validator)
    {
        $errors = (new ValidationException($validator))->errors();
        //return dd($errors);
        if(!empty($errors))
        {
            $transform  = [];
            foreach ($errors as $filed => $name)
            {
                $transform = [
                    $filed => $name[0]
                ];
            }
            throw new HttpResponseException(
                response()->json([
                        'status'    => 'error',
                        'errors'    => $transform
                ],
                    JsonResponse::HTTP_BAD_REQUEST
                )
            );

        }
       /* throw (new ValidationException($validator))
            ->errorBag($this->errorBag)
            ->redirectTo($this->getRedirectUrl());*/
    }

}
