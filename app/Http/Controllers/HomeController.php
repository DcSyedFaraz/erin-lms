<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(){
        return view('home');
    }

    public function program(){
        return view('program');
    }

    public function blog(){
        return view('blog');
    }

    public function about(){
        return view('about');
    }

    public function contact(){
        return view('contact');
    }

    public function membership(){
        return view('membership');
    }

    public function lesson(){
        return view('lesson');
    }

}
