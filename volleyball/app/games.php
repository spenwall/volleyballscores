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
        $where = ['tier' => $tier, 'rounds_id' => $round, 'league' => $league];
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
        $roundrank = roundResults::rankForRound($team->id, $round)->rank;
        $wins = games::where('league', $team->league)
        ->where('tier', $team->tier)
        ->where('rounds_id', $round)
        ->where('winner', $roundrank)
        ->get()
        ->count();
        
        return $wins;
    }
}
