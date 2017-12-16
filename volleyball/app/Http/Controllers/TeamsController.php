<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\team;
use App\league;

class TeamsController extends Controller
{
    public function index($leagueName)
    {
        $leagues = new league();
        $league = $leagues->byName($leagueName);
        dd($league);
        $roundResultsByTier = $league->currentRound()->roundResultsByTier();
        $data = ['roundResultsByTier' => $roundResultsByTier];
        return view('teams', $data);
    }

    public function team($id)
    {
        $team = team::find($id);
        return view('team', ['team' => $team]);
    }
}
