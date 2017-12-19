<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use \Carbon\carbon;

class Rounds extends Model
{

    /**
     * The League for a round
     * 
     * @return League
     */
    public function league()
    {
        return $this->belongsTo(League::class);
    }

    /**
     * All results for a round. Sorted by Tier and then rank
     * 
     * @return RoundResults
     */
    public function roundResults()
    {
        return $this->hasMany(roundResults::class)->orderBy('tier')->orderBy('rank');
    }

    /**
     * Round results for a given tier
     * 
     * @return RoundResults
     */
    public function roundResultsForTier($tier)
    {
        return $this->roundResults()->where('tier', $tier)->get();
    }

    /**
     * All Round results grouped by tier
     * 
     * @return RoundResults
     */
    public function roundResultsByTier()
    {
        return $this->roundResults->groupBy('tier');
    }

    /**
     * The round we are currently in. Should be call on a league
     * 
     * @return int Round
     */
    public static function currentRound()
    {
        $current = carbon::now();
        $rounds = self::where('start', '<', $current)
                        ->where('end', '>', $current)
                        ->first();
        return $rounds->round;
    }
    
    /**
     * All rounds from 1 to current
     * 
     * @return Rounds collection
     */
    public static function roundsToDate()
    {
        $current = carbon::now();
        $rounds = self::select('round')->where('start', '<', $current)->get();
        return $rounds;
    }

    /**
     * All games for a round
     * 
     * @return Games
     */
    public function games()
    {
        return $this->hasMany(Games::class);
    }
}