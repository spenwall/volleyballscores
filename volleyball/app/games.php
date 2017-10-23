<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class games extends Model
{
    public static function gamesInTier($tier, $league)
    {
        $where = ['tier' => $tier, 'league' => $league];
        $games = self::where($where)->get();
       
        $team1Games = $games->where('team1', 1);
        dd($team1Games);
    }
}
