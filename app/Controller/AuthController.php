<?php

namespace App\Controller;

use App\Framework\Http\Request;
use App\Models\User;
use App\Util\Hash;
use App\Util\Validator;
use App\Auth\Auth;
use App\Models\UserRole;

class AuthController extends Controller
{
    public function show()
    {
        $title =  'Login Page';
        return view('auth.login', compact('title'));
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

        $user = User::find('email', $request->input('email'));

        if ($user && password_verify($request->input('password'), $user->password)) {
            $userRole = User::with('roles')->findOrFail($user->id);
            Auth::set('user', $user);
            $roles = $userRole->roles;
            $roleNames = [];

            foreach ($roles as $role) {
                $roleNames[] = $role->role_name;
            }
            Auth::set('role', $roleNames);
            if (in_array('admin', $roleNames)) {
                header('Location: /admin/dashboard');
                exit;
            } else {
                header('Location: /');
                exit;
            }
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
            return view('auth.register', ['errors' => $errors, 'title' => 'Register Page', 'input' => compact('fullname', 'email', 'password', 'confirm_password')]);
        }

        $user = User::create([
            'name' => $fullname,
            'email' => $email,
            'password' => Hash::make($password)
        ]);
        Auth::set('user', $user);
        $users = Auth::get('user');

        UserRole::create([
            'role_id' => 2,
            'user_id' => $users->id
        ]);

        $userRole = User::with('roles')->findOrFail($users->id);
        $roleNames = [];

        foreach ($userRole as $role) {
            $roleNames[] = $role->role_name;
        }
        Auth::set('role', $roleNames);
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
