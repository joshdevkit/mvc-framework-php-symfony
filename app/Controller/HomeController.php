<?php

namespace App\Controller;

use App\Framework\Http\Request;
use App\Models\User;
use App\Util\Hash;
use Exception;

class HomeController extends Controller
{
    public function index()
    {
        // $users = User::get();
        $devContent = [
            'developer' => 'JoshDev (Joshua Mendoza Pacho)',
            'tech' => 'Symfony/FashRoute - PHP MVC',
            'message' => 'Welcome to my Php MVC Project'
        ];

        return view('home', ['title' => 'Homepage', 'content' => $devContent]);
    }

    public function create()
    {
        return view('sample.create');
    }

    public function store(Request $request)
    {
        $name = $request->postParams['name'];
        $email = $request->postParams['email'];
        $password = $request->postParams['password'];

        $user = User::create([
            'name' => $name,
            'email' => $email,
            'password' => Hash::make($password),
        ]);

        if ($user) {
            return "User created successfully!";
        }
    }


    public function show(Request $request, $id)
    {
        try {
            $user = User::findOrFail($id);
            return view('user', ['user' => $user, 'title' => 'User Details with Params']);
        } catch (Exception $e) {
            $errorMessage = $e->getMessage();
            return view('404', compact('errorMessage'));
        }
    }
}
