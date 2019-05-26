<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Task;

class TasksController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tasks = [];
        /*$tasks = Task::all();*/
        if(\Auth::check()){
        $user = \Auth::user();
        $tasks = $user->tasks()->orderBy('created_at','desc')->paginate(10);
        }
        return view('tasks.index',['tasks' => $tasks,]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $task = new Task;
        
        return view('tasks.create',[ 'task'=> $task ,]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [ 
            'status' => 'required|max:10'
            ,'content' => 'required|max:191',]);
            
        $task = new Task;
      /*  $request->user()->tasks()->create([
         'status' => $request->status,//$request->status,
        'content' => $request->content,
            ]);*/ 
        $task->user_id = \Auth::user()->id;
        $task->content = $request->content;
        $task->status = $request->status ;
        $task->save();
        return redirect('/');
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        
       $task = Task::find($id);
       if(is_null($task)){
          return redirect('/'); 
       }
       if(\Auth::user()->id === $task->user_id){
       return view('tasks.show', ['task' => $task,]);
       } else {
          return redirect('/');
       }
     
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
      $task = Task::find($id);
      if(is_null($task)){
          return redirect('/'); 
       }
       if( \Auth::user()->id === $task->user_id ){
      return view('tasks.edit',['task' => $task , ]);
       } else {
           return redirect('/');
       }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
       $this->validate($request, [
           'status' => 'required|max:10'
           ,'content' => 'required|max:191' ,]);
       $task = Task::find($id);
      if(is_null($task)){
          return redirect('/'); 
       }
      if(\Auth::user()->id === $task->user_id ){
       $task->content = $request->content;
       $task->status = $request->status ;
       $task->save();
      }
       return redirect('/');
      
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
      $task = Task::find($id);
      if(is_null($task)){
          return redirect('/'); 
       }
      
      if(\Auth::user()->id === $task->user_id){
      $task->delete();
      }
      return redirect('/');
    }
}
