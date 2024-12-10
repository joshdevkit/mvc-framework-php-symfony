<?php

namespace App\Controller;

use App\Auth\Auth;
use App\Framework\Http\Request;
use App\Models\User;
use App\Util\Hash;
use Exception;

class HomeController extends Controller
{
    public function index()
    {
        $devContent = [
            'developer' => 'JoshDev (Joshua Mendoza Pacho)',
            'tech' => 'Symfony/FastRoute - PHP MVC',
            'message' => 'Welcome to my PHP MVC Project'
        ];
        $title = 'Homepage';

        return view('home', compact('title', 'devContent'));
    }

    public function show(Request $request, $id)
    {
        $title = 'User Details with Params';
        try {
            $user = User::with('info')->findOrFail($id);
            return view('user', compact('title', 'user'));
        } catch (Exception $e) {
            $errorMessage = $e->getMessage();
            return view('404', compact('errorMessage', 'title'));
        }
    }
}
