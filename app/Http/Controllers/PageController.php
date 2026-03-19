<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PageController extends Controller
{
    public function about()
    {
        return view('about');
    }

    public function industries()
    {
        return view('industries');
    }

    public function infrastructure()
    {
        return view('infrastructure');
    }
}
