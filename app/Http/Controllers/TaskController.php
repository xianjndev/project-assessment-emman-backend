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
        $arr = Tasks_1::orderBy('created_at','desc')->get();   
        $statusValue = $arr->map(function($val){
            $val->statusLabel = $val->status == "1" ? 'completed' : 'pending';
            return $val;
        }); return $statusValue;
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
            'isCompleted' => 'required|integer'
        ]);
 
        $task = Tasks_1::create([
            'user_id' => $validated['user_id'],
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'priority' => $validated['priority'] ?? 'medium',
            'isCompleted' => $validated['isCompleted'] ,
            'status' => $validated['status'] ,
        ]);
 
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
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
