<?php


namespace App\Controller;

use App\Framework\Http\Request;
use App\Framework\Http\Response;

class PostController
{
    public function show(Request $request, $id)
    {
        $content = "<h1>Post with Params {$id}</h1>";

        return view('sample',compact('content'));
    }
}
