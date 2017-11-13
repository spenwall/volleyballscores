<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class games extends Model
{
    public static function gamesInTierRound($tier, $round, $league)
    {
        $where = ['tier' => $tier, 'round_id' => $round, 'league' => $league];
        $games = self::where($where)->get();
       
        return $games;
    }

    public static function winner($team1, $team2, $round, $tier)
    {
        $league = $team1->league;
        $where = ['tier' => $tier, 'round_id' => $round, 'league' => $league];
        $winner = self::where($where)->whereIn('team1', [$team1->rank, $team2->rank])
                                     ->whereIn('team2', [$team1->rank, $team2->rank])
                                     ->first();
        if (is_null($winner)) {
            return '-';
        } elseif ($winner->winner == $team1->rank) {
            return 'W';
        } elseif ($winner->winner == $team2->rank) {
            return 'L';
        } elseif ($winner->winner == 0) {
            return 'T';
        }
        return $winner->winner;
    }

    public static function totalWins($team, $round)
    {
        $roundResults = roundResults::where(['team_id' => $team->id, 'round_id' => $round])->first();
        $wins = games::where('league', $team->league)
        ->where('tier', $roundResults->tier)
        ->where('round_id', $round)
        ->where('winner', $roundResults->rank)
        ->get()
        ->count();
        
        return $wins;
    }

    public static function totalLoses($team, $round)
    {
        $roundResults = roundResults::where(['team_id' => $team->id, 'round_id' => $round])->first();
        $loses = games::where('league', $team->league)
        ->where('tier', $roundResults->tier)
        ->where('round_id', $round)
        ->where('winner', '<>', $roundResults->rank)
        ->where('winner', '<>', 0)
        ->where(function ($query) use ($roundResults) {
            $query->where('team1', $roundResults->rank)
                  ->orWhere('team2', $roundResults->rank);
        })
        ->get()
        ->count();

        return $loses;
    }

    public static function totalTies($team, $round)
    {
        $roundResults = roundResults::where(['team_id' => $team->id, 'round_id' => $round])->first();
        $ties = games::where('league', $team->league)
        ->where('tier', $roundResults->tier)
        ->where('round_id', $round)
        ->where('winner', 0)
        ->where(function ($query) use ($roundResults) {
            $query->where('team1', $roundResults->rank)
                  ->orWhere('team2', $roundResults->rank);
        })
        ->get()
        ->count();

        return $ties;
    }
}
