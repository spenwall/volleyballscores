<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\team;

class TeamsController extends Controller
{
    public function index($league)
    {
        $data['teamsByTier'] = team::currentTeamsByTiers($league);
        return view('teams', $data);
    }
}
