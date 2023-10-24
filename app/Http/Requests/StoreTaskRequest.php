<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class StoreTaskRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // dd($this->user());
        $user = $this->user();
        if($user != null){
           return $user->tokenCan('create');
        }
         return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {

        return [
            'name'=>['required','max:255','unique:tasks,name'],
            'description'=>['required'],
            'priority'=>['required',Rule::in(['low','medium','high'])]
        ];
    }
}
