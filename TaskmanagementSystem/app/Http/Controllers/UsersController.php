<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UsersController extends Controller
{
    public function save(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|regex:/^\S*$/',
        ], [
            'email.unique' => 'This email is already registered.',
            'password.min' => 'Password must be at least 8 characters long.',
        ]);
    
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);
    
        return redirect()->route('user.login')->with('success', 'User created successfully!');
    }
    public function addtasks()
    {
        
        $userId = session('LoggedUserInfo');
    
        // Check if the session has the correct user ID
        if (!$userId) {
            return redirect('user/login')->with('fail', 'You must be logged in to access the dashboard');
        }
    
        $LoggedUserInfo = User::find($userId);
        
        // Fetch the tasks for the logged-in user
     
        return view('user.addtask', [
            'LoggedUserInfo' => $LoggedUserInfo,
         ]);
    }
    public function tasks()
    {
        $userId = session('LoggedUserInfo');
    
        // Check if the session has the correct user ID
        if (!$userId) {
            return redirect('user/login')->with('fail', 'You must be logged in to access the dashboard');
        }
    
        $LoggedUserInfo = User::find($userId);
    
        // Fetch the tasks for the logged-in user, ordered by creation date in descending order
        $tasks = $LoggedUserInfo->tasks()->orderBy('created_at', 'desc')->get();
    
        return view('user.tasks', [
            'LoggedUserInfo' => $LoggedUserInfo,
            'tasks' => $tasks, // Pass tasks to the view
        ]);
    }
    
    
    
    
    
    public function check(Request $request)
{
     $request->validate([
        'email' => 'required|email',
        'password' => 'required|min:5|max:12'
    ]);

     $userInfo = User::where('email', $request->email)->first();

     if (!$userInfo) {
        return back()->withInput()->withErrors(['email' => 'Email not found']);
    }
      if ($userInfo->status === 'inactive') {
         return back()->withInput()->withErrors(['status' => 'Your account is inactive']);
        }

     if (!Hash::check($request->password, $userInfo->password)) {
        return back()->withInput()->withErrors(['password' => 'Incorrect password']);
    }

     session([
        'LoggedUserInfo' => $userInfo->id,
        'LoggedUserName' => $userInfo->name,  
    ]);
     return redirect()->route('user.tasks');
}

    

    

    public function logout()
    {
         if (session()->has('LoggedUserInfo')) {
             session()->forget('LoggedUserInfo');
        }
        session()->flush();

         return redirect()->route('user.login');
    }
    
 

}
