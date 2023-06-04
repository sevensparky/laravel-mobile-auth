<?php

namespace SevenSparky\LaravelMobileAuth\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LaravelMobileAuthValidationRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'phone' => 'required|numeric|digits:6'
        ];
    }

    public function  messages()
    {
        return [
            'phone.required' => 'شماره موبایل الزامی می باشد',
            'phone.numeric' => 'شماره موبایل باید عدد باشد',
            'phone.digits' => 'شماره موبایل باید ۱۱ رقم باشد',
        ];
    }

}
