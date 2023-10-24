<?php

namespace App\Http\Resources;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TaskResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // dd($this->user);
        // $user = User::where('id',$this->user_id)->first();
        return [
            'id'=>(string)$this->id,
            'attributes'=>[
                'task name'=>$this->name,
                'description'=>$this->description,
                'priority'=>$this->priority
            ],
            'relationship'=>[
                'id'=>(string) $this->user->id,
                'user'=>$this->user->name,
            ] 
        ];
    }
}
