<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\team;
use App\Games;
use App\League;

class HomeController extends Controller
{
 
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('home');
    }
}
