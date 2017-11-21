<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\games;

class roundResults extends Model
{
    public function team()
    {
        return $this->belongsTo(team::Class);
    }

    public function round()
    {
        return $this->belongsTo(round::Class);
    }

    public function games()
    {
        $where = ['league_id' => $this->league_id, 'tier' => $this->tier, 'rounds_id' => $this->rounds_id];
        return games::where($where)->get();

    }

    public static function rankForRound($team_id, $round)
    {
        $rank = self::select('rank')->where('team_id', $team_id)->where('rounds_id', $round)->first();
        
        return $rank;
    }

    public function recordWins()
    {
        //calculate wins for round/tier/league
        $round = 2;
        $tier = 1;
        $roundResults = $this->where('round_id', $round)
                            ->get();

        foreach ($roundResults as $roundResult) {
            $team = team::find($roundResult->team_id);
            $wins = games::totalWins($team, $round);
            $roundResult->wins = $wins;
            $roundResult->save();
        }
    }

    public function recordLoses()
    {
        $round = 2;
        $tier = 1;
        $roundResults = $this->where('round_id', $round)->get();

        foreach ($roundResults as $roundResult) {
            $team = team::find($roundResult->team_id);
            $loses = games::totalLoses($team, $round);
            $roundResult->loses = $loses;
            $roundResult->save();
        }
    }

    public function recordTies()
    {
        $round = 2;
        $tier = 1;
        $roundResults = $this->where('round_id', $round)->get();

        foreach ($roundResults as $roundResult) {
            $team = team::find($roundResult->team_id);
            $ties = games::totalTies($team, $round);
            $roundResult->ties = $ties;
            $roundResult->save();
        }
    }

    public static function calculateRank($tier, $round)
    {
        $roundResults = self::where('tier', $tier)->where('round_id', $round)
        ->orderBy('wins', 'DESC')
        ->orderBy('rank')
        ->get();
        $count = 1;
        foreach ($roundResults as $result) {
            $result->end_rank = $count;
            $count++;
            $result->save();
        }
    }

    public function calculateNextRoundResults()
    {
        $round = 2;
        $league = 'co-ed';
        $roundResults = $this::where('round_id', $round)
        ->where('league', $league)
        ->get();
        foreach ($roundResults as $results) {
            switch ($results->end_rank) {
                case 1:
                case 2:
                case 3:
                    $newTier = $results->tier - 1;
                    break;
                case 4:
                case 5:
                    $newTier = $results->tier;
                    break;
                case 6:
                case 7:
                case 8:
                    $newTier = $results->tier + 1;
                    break;
            }
            if ($newTier < 1) {
                $newTier = 1;
            } else if ($newTier > team::lowestTier($results->league)) {
                $newTier = $results->tier;
            }
            $newRank = $this->_getNewRank($results->end_rank, $results->tier, $results->league);

            $newResult = new self;
            $newResult->round_id = 3;
            $newResult->team_id = $results->team_id;
            $newResult->rank = $newRank;
            $newResult->tier = $newTier;
            $newResult->league = $results->league;
            $newResult->save();
        }
    }

    private function _getNewRank($endRank, $tier, $league)
    {
        //if highest tier 1,2,3,4,5 stay the same
        //if lowest tier 4,5,6,7,8 stay the same
        switch ($endRank) {
            case 1:
                if ($tier == 1) {
                    return 1;
                } else {
                    return 6;
                }
            case 2:
                if ($tier == 1) {
                    return 2;
                } else {
                    return 7;
                }
            case 3:
                if ($tier == 1) {
                    return 3;
                } else {
                    return 8;
                }
            case 4:
                return 4;
            case 5:
                return 5;
            case 6:
                if ($tier == team::lowestTier($league)) {
                    return 6;
                } else {
                    return 1;
                }
            case 7:
                if ($tier == team::lowestTier($league)) {
                    return 7;
                } else {
                    return 2;
                }
            case 8:
            if ($tier == team::lowestTier($league)) {
                return 8;
            } else {
                return 3;
            }
        }
    }
}
