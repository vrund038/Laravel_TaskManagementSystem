<?php

namespace App\Livewire;

use App\Models\Task as ModelsTask;
use App\Models\User;
use Illuminate\Console\View\Components\Task;
use Livewire\Component;
use Livewire\WithFileUploads;


class AddTask extends Component
{    use WithFileUploads;

    public $title;
    public $description;
    public $completed = false;
    public $due_date;
    public $image;
    public $user_id;

    protected $rules = [
        'title' => 'required|string|max:255',
        'description' => 'nullable|string',
        'completed' => 'boolean',
        'due_date' => 'nullable|date',
        'image' => 'nullable|image|max:1024',
    ];

    public function mount()
    {
        $userId = session('LoggedUserInfo');
        
        if (!$userId) {
            // Optionally, you can handle redirection or error handling here
            abort(403, 'Unauthorized'); // Use abort or redirect as appropriate
        }
        
        $LoggedUserInfo = User::find($userId);

        if ($LoggedUserInfo) {
            $this->user_id = $LoggedUserInfo->id;
        } else {
            abort(403, 'User not found'); // Handle the case where the user is not found
        }
    }

    public function submit()
    {
        $this->validate();

        $task = new \App\Models\Task();
        $task->title = $this->title;
        $task->description = $this->description;
        $task->completed = $this->completed;
        $task->due_date = $this->due_date;
        $task->user_id = $this->user_id;

        if ($this->image) {
            $path = $this->image->store('tasks', 'public');
            $task->image = $path;
        }

        $task->save();

        session()->flash('message', 'Task added successfully! <a href="' . route('user.tasks') . '">View Tasks</a>');

        // Optionally, reset the form fields
        $this->reset();
    }

    public function render()
    {
        return view('livewire.add-task');
    }
}

