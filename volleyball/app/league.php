<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use \Carbon\carbon;

class League extends Model
{

    public function teams()
    {
        return $this->hasMany(team::class);
    }

    public function games()
    {
        return $this->hasMany(Games::class);
    }

    public function gamesByTier($tier)
    {
        return $this->games()->where('tier', $tier)->get();
    }

    public function leagueTiers()
    {
        $teams = $this->teams()->get();
        return $teams->groupBy('tier')->keys();
    }

    public function rounds()
    {
        return $this->hasMany(Rounds::class);
    }
    
    public function roundResults()
    {
        return $this->hasMany(RoundResults::class);
    }

    public function round($round)
    {
        return $this->rounds->where('round', $round)->first();
    }

    public function currentRound()
    {
        $current = carbon::now();
        return $this->rounds->where('start', '<', $current)
        ->where('end', '>', $current)
        ->first();
    }

    public static function byName($name)
    {
        return self::where('league_name', $name)->first();
    }

    public static function leagues()
    {
        return self::all();
    }

    public function roundsToDate()
    {
        $current = carbon::now();
        return $this->rounds->where('start', '<', $current)->sortBy('round');
    }

    public function nextRound($round)
    {
        $nextRound = $round->round;
        $nextRound++;
        return $this->rounds->where('round', $nextRound)->first();
    }
}
