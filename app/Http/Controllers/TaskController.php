<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;
use Illuminate\Support\Facades\Validator;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tasks = Task::all();
        return response()->json(['tasks' => $tasks], 200);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        $task = Task::create([
            'title' => $request->input('title'),
            'description' => $request->input('description'),
        ]);

        return response()->json(['task' => $task], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Task $task)
    {
        if (!$task) {
            return response()->json(['error' => 'Task not found'], 404);
        }

        return response()->json(['task' => $task], 200);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Task $task)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required_without_all:description|string|max:255',
            'description' => 'required_without_all:title|nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        $attributesToUpdate = [];

        if ($request->has('title')) {
            $attributesToUpdate['title'] = $request->input('title');
        }

        if ($request->has('description')) {
            $attributesToUpdate['description'] = $request->input('description');
        }

        $task->update($attributesToUpdate);

        return response()->json(['message' => "Task updated successfully", 'task' => $task], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Task $task)
    {
        $task->delete();

        return response()->json(['message' => 'Task deleted successfully'], 200);
    }
}
