<?php

namespace App\Http\Controllers;

use App\Http\Requests\DeleteTaskRequest;
use App\Models\Task;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Http\Resources\TaskCollection;
use App\Http\Resources\TaskResource;
use Illuminate\Support\Facades\Auth;
use App\Traits\HttpResponses;

class TaskController extends Controller
{
    use HttpResponses;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user= Auth::user();
        return new TaskCollection(Task::where('user_id',$user->id)->get());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTaskRequest $request)
    {
  
        return new TaskResource(Task::create([
            'user_id'=>Auth::user()->id,
            'name'=>$request->name,
            'description'=>$request->description,
            'priority'=>$request->priority
        ]));
    }

    /**
     * Display the specified resource.
     */
    public function show(Task $task)
    {
        return $this->checkAuthorisation($task)? new TaskResource($task): $this->error('','You are not authrize to access this task',401);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTaskRequest $request, Task $task)
    {
        $task->update($request->validated());
        return response()->json("product updated");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Task $task)
    {
        if($this->checkAuthorisation($task)){
            $task->delete();
             return $this->success('','Task Successfully deleted.',200);
        }else{
             return $this->error('','You are not authrize to delete this task',401);
        }
    }

    protected function checkAuthorisation(Task $task){
        if($task->user_id === Auth::user()->id){
            return true;
        }else{
            return false;
        }       
    }
}
