<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use \Datetime;
use \App\rounds;

class team extends Model
{

    CONST COL_ID = 'id';
    CONST COL_TEAM_NAME = 'team_name';
    CONST COL_CONTACT_NAME = 'contact_name';
    CONST COL_CONTACT_EMAIL = 'contact_email';
    CONST COL_CONTACT_PHONE = 'contact_phone';
    CONST COL_LEAGUE = 'league';
    CONST COL_TIER = 'tier';
    CONST COL_RANK = 'rank';

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
            $team1 = $this->where(self::COL_RANK, $game->team1)->where(self::COL_TIER, $this->tier)->where(self::COL_LEAGUE, $this->league)->first();
            $team2 = $this->where(self::COL_RANK, $game->team2)->where(self::COL_TIER, $this->tier)->where(self::COL_LEAGUE, $this->league)->first();
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

    /**
     * Returns all the teams that match the team and league that is passed in
     * 
     * @param int $id - the id of the team
     * 
     * @return collection of the teams
     */
    public function allLeagueTierTeams()
    {
        $allTeams = $this->where($this::COL_TIER, $this->tier)
                         ->where($this::COL_LEAGUE, $this->league)
                         ->orderBy($this::COL_RANK)
                         ->get();
        return $allTeams;

    }

    public function leagueTiers()
    {
        $tiers = $this->select($this::COL_TIER)->where($this::COL_LEAGUE, $this->league)->get()->unique();
        return $tiers;
    }

    public function teamsInTier($tier)
    {
        $teams = $this->where($this::COL_LEAGUE, $this->league)->where($this::COL_TIER, $tier);
        return $teams;
    }
}
