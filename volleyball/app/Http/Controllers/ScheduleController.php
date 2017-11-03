<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\team;

class ScheduleController extends Controller
{
    public function index($league)
    {
        $tiers = team::leagueTiers($league);
        return view('schedule', ['tiers' => $tiers]);
    }
}
