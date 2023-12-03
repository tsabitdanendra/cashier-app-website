<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Auth;
use Illuminate\Support\Facades\Session;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('login');
    }

    public function login(Request $request)
    {
        // Validate the login credentials
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);
    
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $user = User::where('email', $request->email)->first();
            $name = $user->name;
            $userId = $user->id;
            
            session::put('name',$name);
            session::put('userId',$userId);
            // @dd($name);
            
            return redirect('/transaction');//->with('name', $name);
        } else {
            return redirect()->back()->withErrors(['message' => 'Invalid credentials'])->withInput();
        }
    }

    public function logout()
    {
        Auth::logout();
        session()->forget('newOrder');
        session()->forget('name');      


        return redirect('/login'); // Redirect to your desired page after logout
    }
    
}
