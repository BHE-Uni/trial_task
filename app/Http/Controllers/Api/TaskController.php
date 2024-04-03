<?php

namespace App\Http\Controllers\Api;

use App\Models\Task;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\TaskStoreRequest;
use App\Http\Requests\TaskUpdateRequest;

class TaskController extends Controller
{

    public function index()
    {
        $tasks = Task::paginate(10);
        return response()->json($tasks);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(TaskStoreRequest $request)
    {
        $task = Task::create($request->validated());
        return response()->json($task, 201);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(TaskUpdateRequest $request, string $id)
    {
        $task = Task::findOrFail($id);
        $task->update($request->validated());
        return response()->json($task);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $task = Task::findOrFail($id);
        $task->delete();

        return response()->json(null, 204);
    }

        public function stats()
    {
        $totalTasks = Task::count();
        $completedTasks = Task::where('completed', true)->count();
        $pendingTasks = Task::where('completed', false)->count();

        return response()->json([
            'totalTasks' => $totalTasks,
            'completedTasks' => $completedTasks,
            'pendingTasks' => $pendingTasks,
        ]);
    }
}
