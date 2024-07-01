<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class ContactCreateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // ketika sudah login
        return $this->user() != null; // mendapatkan data user yang saat ini sedang login
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        // rules / auturan validasi
        return [
            "first_name" => ["required", "max:100"],
            "last_name" => ["nullable", "max:100"],
            "email" => ["nullable", "max:200", "email"],
            "phone" => ["nullable", "max:20"],
        ];
    }

    // response bad request, jika user salah memasukan aturan validasi
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response([
            "errors" => $validator->getMessageBag()
        ], 400));
    }

}
