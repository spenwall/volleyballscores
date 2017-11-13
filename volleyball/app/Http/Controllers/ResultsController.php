<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\team;
use App\games;
use App\rounds;
use App\roundResults;

class ResultsController extends Controller
{
    private $_round;
    private $_league;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('auth');
    }

    /**
     * Show the results for current tier
     *
     * @return \Illuminate\Http\Response
     */
    public function index($league, $round)
    {
         
        $roundResults = new roundResults();
        $roundResults->recordWins();
        $roundResults->recordLoses();
        $roundResults->recordTies();
        roundResults::calculateRank(4, 2);
        $this->_round = $round;
        $this->_league = $league;
        $resultsByTier = $this->_resultsByTier();
        $teamResults = $this->_teamsResults();
        $teamsByTiers = team::teamsByTiers($league, $round);
        $data = array('resultsByTier' => $resultsByTier, 'teamsByTiers' => $teamResults);
        return view('results', $data);
    }

    private function _resultsByTier()
    {
        $teamsByTiers = team::teamsByTiers($this->_league, $this->_round);
        foreach($teamsByTiers as $tier => $teams){
            foreach ($teams as $team1) {
                foreach ($teams as $team2) {
                    $results[$tier][$team1->rank][$team2->rank] = $this->_winner($team1, $team2, $tier);
                }
            }
        }
        if (isset($results)) {
            return $results;
        } else {
            return false;
        }
    }

    private function _winner($t1, $t2, $tier)
    {
        $winner = games::winner($t1, $t2, $this->_round, $tier);

        return $winner;
    }

    private function _teamsResults()
    {
        $teamsByTier = team::teamsByTiers($this->_league, $this->_round);
        foreach ($teamsByTier as $tier => $teams)
            foreach($teams as $team_num => $team) {
                $teamObject = team::find($team['id']);
            }
        return $teamsByTier;
    }
}
