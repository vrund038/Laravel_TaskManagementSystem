<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;

class TasksController extends Controller
{
    public function update(Request $request, $taskId)
    {
        $task = Task::findOrFail($taskId);
    
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'due_date' => 'required|date',
            'completed' => 'required|boolean',
            'image' => 'nullable|image|max:2048',
        ]);
    
        $task->title = $request->input('title');
        $task->description = $request->input('description');
        $task->due_date = $request->input('due_date');
        $task->completed = $request->input('completed');
    
        if ($request->hasFile('image')) {
            // Handle the image upload
            $imagePath = $request->file('image')->store('tasks', 'public');
            $task->image = $imagePath;
        }
    
        $task->save();
    
        return redirect()->route('user.tasks')->with('success', 'Task updated successfully');
    }
        public function destroy($taskId)
    {
        $task = Task::findOrFail($taskId);
        $task->delete();
    
        return redirect()->route('user.tasks')->with('success', 'Task deleted successfully');
    }

    public function edit($id)
    {
        // Get the logged-in user ID from session
        $userId = session('LoggedUserInfo');
    
        // Check if the session has the correct user ID
        if (!$userId) {
            return redirect('user/login')->with('fail', 'You must be logged in to access the dashboard');
        }
    
        // Find the logged-in user
        $LoggedUserInfo = User::find($userId);
    
        // Find the task by ID
        $task = Task::find($id);
    
        if (!$task) {
            return redirect()->route('user.tasks')->with('fail', 'Task not found');
        }
    
        // Ensure due_date is a Carbon instance
        $task->due_date = \Carbon\Carbon::parse($task->due_date);
    
        // Return the view with task and user info
        return view('user.edittask', [
            'task' => $task,
            'LoggedUserInfo' => $LoggedUserInfo
        ]);
    }
    
    public function show($id)
    {$userId = session('LoggedUserInfo');
    
        // Check if the session has the correct user ID
        if (!$userId) {
            return redirect('user/login')->with('fail', 'You must be logged in to access the dashboard');
        }
    
        $LoggedUserInfo = User::find($userId);
        
        $task = Task::findOrFail($id);
        
        return view('user.viewtask', [
            'task' => $task,
            'LoggedUserInfo' => $LoggedUserInfo,

         ]);
    }
}
