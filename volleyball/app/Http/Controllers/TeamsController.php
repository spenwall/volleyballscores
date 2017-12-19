<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\team;
use App\League;

class TeamsController extends Controller
{
    public function index($leagueName)
    {
        $leagues = new League();
        $league = $leagues->byName($leagueName);
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
