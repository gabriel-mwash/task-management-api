<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
  public function store(Request $request) {
    $validated = $request->validate([
      'title' => 'required|string|max:255',
      'due_date' => 'required|date|after_or_equal:today',
      'priority' => 'required|in:low,medium,high',
    ]);

    $existingTask = Task::where('title', $validated['title'])
      ->where('due_date', $validated['due_date'])
      ->first();

    if ($existingTask) {
      return response()->json([
        'message' => 'A task with the same title and due date'], 442);
    }

    $task = Task::create([
      'title' => $validated['title'],
      'due_date' => $validated['due_date'],
      'priority' => $validated['priority'],
      'status' => 'pending',
    ]);

    return response()->json([
      'message' => 'Task created successfully.',
      'task' => $task
    ], 201);
  }

  public function index(Request $request) {
    $query = Task::query();
    if ($request->has('status')) {
      $query->where('status', $request->status);
    }

    $tasks = $query
      ->orderByRaw("FIELD(priority, 'high', 'medium', 'low')")
      ->orderBy('due_date', 'asc')
      ->get();

    if ($tasks->isEmpty()) {
      return response()->json([
        'message' => 'No tasks found.',
        'tasks' => []
      ], 200);
    }

    return response()->json([
      'message' => 'Tasks retrieved successfully.',
      'tasks' => $tasks
    ], 200);
  }

  public function updateStatus(Request $request, $id) {
    $validated = $request->validate([
      'status' => 'required|in:pending,in_progress,done',
    ]);

    $task = Task::find($id);

    if (!$task) {
      return response()->json([
        'message' => 'Task not found.',
      ], 404);
    }

    $currentStatus = $task->status;
    $newStatus = $validated['status'];

    $allowedTransactions = [
      'pending' => 'in_progress',
      'in_progress' => 'done',
      'done' => null,
    ];

    if ($allowedTransactions[$currentStatus] !== $newStatus) {
      return response()->json([
        'message' => 'Invalid status transition. Status can only move forward one step.'], 442);
    }

    $task->status = $newStatus;
    $task->save();

    return response()->json([
      'message' => 'Task status updated successfully.',
      'task' => $task
    ], 200);
  }

  public function destroy($id) {
    $task = Task::find($id);
    if (!$task) {
      return response()->json([
        'message' => 'Task not found.'
      ], 404);
    }

    if ($task->status !== 'done') {
      return response()->json([
        'message' => 'only completed tasks can be deleted.'
      ], 403);
    }

    $task->delete();

    return response()->json([
      'message' => 'Task deleted successfully.'
    ], 200);
  }

  public function report(Request $request) {
    $validated = $request->validate([
      'date' => 'required|date',
    ]);

    $date = $validated['date'];

    $priorities = ['high', 'medium', 'low'];
    $statuses = ['pending', 'in_progress', 'done'];

    $summary = [];

    foreach ($priorities as $priority) {
      $summary[$priority] = [];

      foreach ($statuses as $status) {
        $summary[$priority][$status] = Task::where('due_date', $date)
          ->where('priority', $priority)
          ->where('status', $status)
          ->count();
      }
    }

    return response()->json([
      'date' => $date,
      'summary' => $summary
    ], 200);
  }
}

