<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use \Datetime;
use \App\rounds;
use \App\roundResults;
use App\games;

class team extends Model
{

    CONST COL_ID = 'id';
    CONST COL_TEAM_NAME = 'team_name';
    CONST COL_CONTACT_NAME = 'contact_name';
    CONST COL_CONTACT_EMAIL = 'contact_email';
    CONST COL_CONTACT_PHONE = 'contact_phone';
    CONST COL_LEAGUE = 'league_id';
    CONST COL_TIER = 'tier';
    CONST COL_RANK = 'rank';

    public function roundResults()
    {
        return $this->hasMany(roundResults::class);
    }

    public function league()
    {
        return $this->belongsTo(league::class);
    }

    public function currentGames()
    {
        $currentRank = $this->currentRank();
        $games = games::where('tier', $this->tier)
                    ->where('round_id', rounds::currentRound())
                    ->where('league_id', $this->league->id)
                    ->where(function ($query) {
                        $query->where('team1', $this->currentRank())
                              ->orWhere('team2', $this->currentRank());
                    })
                    ->get();
        $gamesArray = array();
        foreach ($games as $game) {
            $team1 = $this->where(self::COL_RANK, $game->team1)
            ->where(self::COL_TIER, $this->tier)
            ->first();
            $team2 = $this->where(self::COL_RANK, $game->team2)
            ->where(self::COL_TIER, $this->tier)
            ->first();
            $date = new DateTime($game->date);
            $date = $date->format('F j, Y');
            $gamesArray[] = array('id' => $game->id,
                                    'team1' => $game->team1,
                                    'team1_id' => $team1->id,
                                    'team1_name' => $team1->team_name,
                                    'team1_rank' => $team1->currentRank(), 
                                    'team2' => $game->team2,
                                    'team2_id' => $team2->id,
                                    'team2_name' => $team2->team_name,
                                    'team2_rank' => $team2->currentRank(),
                                    'date' => $date,
                                    'winner' => $game->winner);
        }
        return $gamesArray;
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
                         ->where($this::COL_LEAGUE, $this->league->id)
                         ->orderBy($this::COL_RANK)
                         ->get();
        return $allTeams;

    }

    public static function leagueTiers($league)
    {
        $tiers = self::select(self::COL_TIER)->where(self::COL_LEAGUE, $league)
        ->groupBy(self::COL_TIER)->get();
        return $tiers;
    }

    public static function lowestTier($league)
    {
        $tiers = self::leagueTiers($league);
        $lowestTier = $tiers->max('tier');
        return $lowestTier;
    }

    public function teamsInTier($tier)
    {
        $teams = $this->where($this::COL_LEAGUE, $this->league)->where($this::COL_TIER, $tier)->get();
        return $teams;
    }

    public static function teamsForRoundAndTier($round, $tier, $league)
    {
       
        $teams = self::select('teams.id', 'round_results.rank', 'team_name',
        'contact_name', 'round_results.tier', 'contact_phone', 
        'contact_email', 'teams.league', 'round_results.end_rank')
                                ->where('round_id', $round)
                                ->where('round_results.tier', $tier)
                                ->where('teams.league', $league)
                                ->join('round_results', 'teams.id', '=', 'round_results.team_id')
                                ->orderBy('round_results.rank')
                                ->get();
        foreach ($teams as $team) {
            $team->wins = games::totalWins($team, $round);
            $team->loses = games::totalLoses($team, $round);
            $team->ties = games::totalTies($team, $round);
        }
        return $teams;
    }

    public static function currentTeamsByTiers($league)
    {
        return self::teamsByTiers($league, rounds::currentRound());
    }

    public static function teamsByTiers($league, $round)
    {
        $tiersInLeague = self::leagueTiers($league);
        $teamsInTiers = array();
        foreach ($tiersInLeague as $tier)
        {
            $teamsInTiers[$tier->tier] = team::teamsForRoundAndTier($round, $tier->tier, $league);
        }
        return $teamsInTiers;
    }

    public function currentRank()
    {
        $currentRound = rounds::currentRound();
        $roundResults = $this->roundResults;
        $currentRank = $roundResults->where('round_id', $currentRound)->first()->rank;
        
        return $currentRank;
    }
}
