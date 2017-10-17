<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\team;
use App\games;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();
        $name = $user->name;
        $team = $user->team;
        $teamObject = team::find($team->id);
        $games = $teamObject->games();
        return view('home', ['name' => $name, 'team' => $team, 'games' => $games]);
    }

    public function scores()
    {
        $game = request('game');
        $winner = request('winner');
        $gameRow = games::find($game);
        $gameRow->winner = $winner;
        $gameRow->save();
        $user = Auth::user();
        $name = $user->name;
        $team = $user->team;
        $teamObject = team::find($team->id);
        $games = $teamObject->games();
        return view('home', ['name' => $name, 'team' => $team, 'games' => $games]);
    }
}
