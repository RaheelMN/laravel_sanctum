<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use App\Traits\HttpResponses;
use Illuminate\Validation\Rule;

class UpdateTaskRequest extends FormRequest
{
    use HttpResponses;
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // dd($this->json("user_id"));
        /** @var \App\Models\User $user **/
        $user = Auth::user();

        //if user is not logged in
        if($user !== null){
            return $user->tokenCan('update');
        }else
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $method=$this->method();
        if($method==='PUT'){
            return [
                "name"=>["required","unique:tasks,name,".$this->task->id],
                "description"=>["required","string"],
                "priority"=>["required",Rule::in(["low","medium","high"])]
            ];
        }else{
            return [
                "name"=>["sometimes","required","unique:tasks,name,".$this->task->id],
                "description"=>["sometimes","required","string"],
                "priority"=>["sometimes","required",Rule::in(["low","medium","high"])]
            ];            
        }
    }
}
