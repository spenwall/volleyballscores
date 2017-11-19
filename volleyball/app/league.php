<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class league extends Model
{

    public function teams()
    {
        return $this->hasMany(team::class);
    }

    public function games()
    {
        return $this->hasMany(games::class);
    }

    public function rounds()
    {
        return $this->hasMany(rounds::class);
    }
    
    public function roundResults()
    {
        return $this->hasMany(roundResults::class);
    }

    public function round($round)
    {
        return $this->rounds->where('round', $round)->first();
    }

    public function teamsForRound()
    {
        $teams = $this->teams()->union($this->roundResults())->get();
        dd($teams);
    }
}
