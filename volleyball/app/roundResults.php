<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class roundResults extends Model
{
    public function team()
    {
        return $this->belongsTo('team');
    }

    public function round()
    {
        return $this->belongsTo(round::Class);
    }

    public static function rankForRound($team_id, $round)
    {
        $rank = self::select('rank')->where('team_id', $team_id)->where('round_id', $round)->first();
        
        return $rank;
    }

}
