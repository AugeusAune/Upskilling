<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    
   public function register(Request $request) {
       $request->validate([
           'name' => 'required',
           'email' => ['required', 'email', 'unique:users'],
           'password' => 'required',
       ]);

       User::insert([
           'name' => $request->name,
           'email' => $request->email,
           'password' => bcrypt($request->password),
           'role' => 2
       ]);
       return redirect('/login')->with('success', 'Success Add Account');
   }

   public function login(Request $request){
       $validate = [
        'email' => ['required', 'email'],
        'password' => 'required'
       ];
        $credentials = $request->validate($validate);
        if(Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect('/');
        }
        return back()->withErrors([
            'error' => 'Gagal Login, Tidak Ada User Seperti yang diinputkan'
        ]);
   }

   public function logout(Request $request){
        $request->session()->invalidate();
        return redirect('/login')->with('success', 'Success Logout');
   } 

}
