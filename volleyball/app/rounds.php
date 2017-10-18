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
}
