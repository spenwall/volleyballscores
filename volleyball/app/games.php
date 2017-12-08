<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Games extends Model
{

    protected $dates = [
        'created_at',
        'updated_at',
        'date',
    ];

    public function league()
    {
        return $this->belongsTo(league::class);
    }

    public static function gamesInTierRound($tier, $round, $league)
    {
        $where = ['tier' => $tier, 'rounds_id' => $round, 'league_id' => $league];
        $games = self::where($where)
        ->orderBy('date')
        ->orderBy('location')
        ->orderBy('court')
        ->get();
       
        return $games;
    }

    public function team1()
    {
        return $this->getTeam($this->team1);
    }

    public function team2()
    {
        return $this->getTeam($this->team2);
    }

    public function getTeam($team)
    {
        return $roundResults = $this->league
        ->round($this->rounds_id)->roundResults
        ->where('rank', $team)
        ->where('tier', $this->tier)
        ->first()
        ->team;
    }

    public static function winner($games, $team1, $team2)
    {
        $winner = $games->whereIn('team1', [$team1, $team2])
                        ->whereIn('team2', [$team1, $team2])
                        ->first();

        if (!is_null($winner)) {
            switch ($winner->winner) {
                case $team1:
                    return 'W';
                case $team2:
                    return 'L';
                case 0: 
                    return 'T';
                case -1:
                    return '-';
            }
        } else {
            return '-';
        }
    }

    public static function totalWins($games, $teamRank)
    {
        return $games->where('winner', $teamRank)->count();
    }

    public static function totalLoses($games, $teamRank)
    {
        $teamsGames = $games->filter(function ($game) use ($teamRank) {
            return ($game->team1 == $teamRank || $game->team2 == $teamRank);
        });
        $loses = $teamsGames->where('winner', '<>', $teamRank)
        ->where('winner', '<>', 0)
        ->where('winner', '<>', -1)
        ->count();

        return $loses;
    }

    public static function totalTies($games, $teamRank)
    {
        $teamsGames = $games->filter(function ($game) use ($teamRank) {
            return ($game->team1 == $teamRank || $game->team2 == $teamRank);
        });
        $ties = $teamsGames
        ->where('winner', 0)
        ->count();

        return $ties;
    }
}
