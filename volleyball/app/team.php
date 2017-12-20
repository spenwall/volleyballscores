<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use \Datetime;
use \App\Rounds;
use \App\RoundResults;
use App\Games;

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
        return $this->hasMany(RoundResults::class);
    }

    public function currentRoundResults()
    {
        return $this->roundResults->where('rounds_id', $this->league->currentRound()->id)->first();
    }

    public function currentRank()
    {
        return $this->currentRoundResults()->rank;
    }

    public function currentTier()
    {
        return $this->currentRoundResults()->tier;
    }

    public function currentGamesForTier()
    {
        return $this->league->currentRound()->games->where('tier', $this->currentTier());
    }

    public function league()
    {
        return $this->belongsTo(League::class);
    }

    public function players()
    {
        return $this->hasMany(Player::class);
    }

    public function currentGames()
    {
        $currentRank = $this->currentRank();
        $currentTierGames = $this->currentGamesForTier();
        $games = $currentTierGames->filter(function ($game) use ($currentRank) {
            return ($game->team1 == $currentRank || $game->team2 == $currentRank);
        });
        return $games;
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
            $team->wins = Games::totalWins($team, $round);
            $team->loses = Games::totalLoses($team, $round);
            $team->ties = Games::totalTies($team, $round);
        }
        return $teams;
    }

    public static function currentTeamsByTiers($league)
    {
        return self::teamsByTiers($league, Rounds::currentRound());
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
}
