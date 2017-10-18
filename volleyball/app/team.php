<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use \Datetime;
use \App\rounds;

class team extends Model
{
    public function games()
    {
        $games = games::where('team1', $this->rank)
                    ->orWhere('team2', $this->rank)
                    ->where('league', $this->league)
                    ->where('tier', $this->tier)
                    ->where('round', rounds::currentRound())
                    ->get();
        $completeGames = array();
        foreach ($games as $game) {
            $team1 = $this->where('rank', $game->team1)->where('tier', $this->tier)->where('league', $this->league)->first();
            $team2 = $this->where('rank', $game->team2)->where('tier', $this->tier)->where('league', $this->league)->first();
            $date = new DateTime($game->date);
            $date = $date->format('F j, Y');
            $completeGames[] = array('id' => $game->id,
                                    'team1' => $game->team1,
                                    'team1_name' => $team1->team_name,
                                    'team1_rank' => $team1->rank,
                                    'team2' => $game->team2,
                                    'team2_name' => $team2->team_name,
                                    'team2_rank' => $team2->rank,
                                    'date' => $date,
                                    'winner' => $game->winner);
        }
        return $completeGames;
    }
}
