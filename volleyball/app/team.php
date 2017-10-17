<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use \Datetime;

class team extends Model
{
    public function games()
    {
        $games = games::where('team1', $this->id)->orWhere('team2', $this->id)->get();
        $completeGames = array();
        foreach ($games as $game) {
            $team1Name = $this->_teamName($game->team1);
            $team1Rank = $this->_teamRank($game->team1);
            $team2Name = $this->_teamName($game->team2);
            $team2Rank = $this->_teamrank($game->team2);
            $date = new DateTime($game->date);
            $date = $date->format('F j, Y');
            $completeGames[] = array('id' => $game->id,
                                    'team1' => $game->team1,
                                    'team1_name' => $team1Name,
                                    'team1_rank' => $team1Rank,
                                    'team2' => $game->team2,
                                    'team2_name' => $team2Name,
                                    'team2_rank' => $team2Rank,
                                    'date' => $date,
                                    'winner' => $game->winner);
        }
        return $completeGames;
    }

    private function _teamName($teamId)
    {
        $team = $this->find($teamId);
        return $team->team_name;
    }

    private function _teamRank($teamId)
    {
        $team = $this->find($teamId);
        return $team->rank;
    }
}
