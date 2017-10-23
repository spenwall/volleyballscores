<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use \Carbon\carbon;

class rounds extends Model
{
    public static function currentRound()
    {
        $current = carbon::now();
        $rounds = self::where('start', '<', $current)
                        ->where('end', '>', $current)
                        ->first();
        return $rounds->round;
    }

    public static function roundsToDate()
    {
        $current = carbon::now();
        $rounds = self::select('round')->where('start', '<', $current)->get();
        return $rounds;
    }

    public function games()
    {
        return $this->hasmany(games::class);
    }

    public function gamesByLeague($league)
    {
        $games = $this->games()->get();
        return $games->where('league', $league);
    }

    public function gamesByLeagueTier($league, $tier)
    {
        $games = $this->games()->get();
        return $games->where('league', $league)->where('tier', $tier);
    }
}
