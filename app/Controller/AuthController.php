<?php

namespace App\Controller;

use App\Framework\Http\Request;
use App\Models\User;
use App\Util\Hash;
use App\Util\Validator;
use App\Auth\Auth;

class AuthController extends Controller
{
    public function show()
    {
        return view('auth.login', ['title' => 'Login Page']);
    }

    public function login(Request $request)
    {
        $rules = [
            'email' => 'required',
            'password' => 'required'
        ];

        $validator = new Validator();
        $input = [
            'email' => $request->input('email'),
            'password' => $request->input('password')
        ];

        if (!$validator->validate($input, $rules)) {
            $errors = $validator->errors();
            return view('auth.login', ['errors' => $errors, 'input' => $input, 'title' => 'Login Page']);
        }

        $user = User::find('email', $input['email']);
        if ($user && password_verify($input['password'], $user->password)) {
            Auth::set('user', $user);
            return redirect('/');
        } else {
            $validator->addError('email', 'These credentials do not match our records.');
            $errors = $validator->errors();
            return view('auth.login', ['errors' => $errors, 'input' => $input, 'title' => 'Login Page']);
        }
    }


    public function register()
    {
        return view('auth.register', ['title' => 'Register Page']);
    }


    public function create(Request $request)
    {
        $fullname = $request->input('fullname');
        $email = $request->input('email');
        $password = $request->input('password');
        $confirm_password = $request->input('confirm_password');
        $rules = [
            'fullname' => 'required',
            'email' => 'required|unique:users',
            'password' => 'required|min:8',
            'confirm_password' => 'required|confirm_password'
        ];

        $validator = new Validator();

        if (!$validator->validate([
            'fullname' => $fullname,
            'email' => $email,
            'password' => $password,
            'confirm_password' => $confirm_password
        ], $rules)) {
            $errors = $validator->errors();
            return view('auth.register', ['errors' => $errors, 'input' => compact('fullname', 'email', 'password', 'confirm_password'), 'title' => 'Register Page']);
        }

        $user = User::create([
            'name' => $fullname,
            'email' => $email,
            'password' => Hash::make($password)
        ]);

        // $role = 
        // dd($user);


        if ($user) {
            $user = Auth::set('user', $user);
            return redirect('/');
        }
    }

    public function destroy(Request $request)
    {
        if ($request->isPost()) {
            Auth::destroy();
            return redirect('/');
        }
    }
}
