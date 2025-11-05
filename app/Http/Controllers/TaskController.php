<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Tasks_1; 
use Illuminate\Http\Request;

use function PHPSTORM_META\map;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    { 
        // $arr = Tasks_1::orderBy('created_at','desc')->get();   
        $arr = Tasks_1::orderBy('order','asc')->get();   
        $statusValue = $arr->map(function($val){
            $val->statusLabel = $val->status == "1" ? 'completed' : 'pending';
            return $val;
        }); 
        return response()->json($statusValue) ;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {   
        $validated = $request->validate([
            'user_id' => 'required',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required',
            'priority' => 'nullable|string|in:low,medium,high',
            'isCompleted' => 'required|integer',
            'order' => 'required'
        ]);

        logger([
              'user_id' => $validated['user_id'],
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'priority' => $validated['priority'] ?? 'medium',
            'isCompleted' => $validated['isCompleted'] ,
            'status' => $validated['status'] ,
            'order' => $validated['order']
        ]);


        $task = Tasks_1::create([
            'user_id' => $validated['user_id'],
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'priority' => $validated['priority'] ?? 'medium',
            'isCompleted' => $validated['isCompleted'] ,
            'status' => $validated['status'] ,
            'order' => $validated['order']
        ]);

        logger($task->json_decode());
        
 
        return response()->json([
            'status' => true,
            'message' => 'Task saved successfully!',
            'data' => $task
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */

    public function sortOrder(Request $request){ 
        // TODO:
    //fix naming convetion
    //refactor line 77 78;  
        $orderTo = $request->to; 
         
        $rangeTasks = Tasks_1::whereBetween('id', [$request->from, $request->to])  
        ->get();  

        if($rangeTasks->isEmpty()){  
            
            $newOrder = 0;

            $getId = Tasks_1::find([$request->from , $request->to]);
            $rangeId = [];
            foreach ($getId as $val) {
                $rangeId[] = $val->id;
            }
            $getLists = Tasks_1::whereBetween('id', $rangeId)  
            ->get(); 
            
            $getLists = $getLists->map(function ($task) use($orderTo, &$newOrder){ 
                if($task['id'] == $orderTo){
                    $task['order'] = $orderTo+1;   
                    $newOrder =  $task['order']; 
                }else{
                    $newOrder = $newOrder-1;
                    $task['order'] = $newOrder;   
                } 
                return $task;
            }); 
 
            return response()->json($getLists);    
        }else{ 
            $rangeTasks = $rangeTasks->map(function ($task) use(&$orderTo){ 
                $orderTo = $orderTo -1;
                $task['order'] = $orderTo;
                return $task;
            });
            
            return response()->json($rangeTasks);
        } 
        return 'connected';
    }

    public function update(Request $request, string $id)
    {
        $tasks = $request->tasks;

        foreach ($tasks as $task) {
            Tasks_1::where('id', $task['id']) 
                ->update(['order' => $task['order']]);
        }

    return response()->json(['message' => 'Order updated successfully']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
