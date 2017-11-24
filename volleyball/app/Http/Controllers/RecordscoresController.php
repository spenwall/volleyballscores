<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\rounds;
use App\team;
use App\games;


class RecordscoresController extends Controller
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
        $data = $this->_data();
        return view('record', $data);
    }

    public function scores()
    {
        $game = request('game');
        $winner = request('winner');
        
        $gameRow = games::find($game);
        $gameRow->winner = $winner;
        $gameRow->save();
        
        $data = $this->_data();
        $data['gameUpdated'] = $game;
        return view('record', $data);
    }

    private function _data()
    {
        $user = Auth::user();
        $name = $user->name;
        $team = $user->team;
        $league = $team->league;
        $games = $team->currentGames();
        $round = rounds::currentRound();
        $data = array('name' => $name, 'team' => $team, 'games' => $games, 'round' => $round);
        return $data;
    }

    
}
