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

    public function rounds()
    {
        return $this->belongsTo(rounds::Class);
    }

    public function league()
    {
        return $this->belongsTo(league::Class);
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
        $wins = games::totalWins($this->games(), $this->rank);

        $this->wins = $wins;
        $this->save();
    }

    public function recordLoses()
    {
        $loses = games::totalLoses($this->games(), $this->rank);

        $this->loses = $loses;
        $this->save();
    }

    public function recordTies()
    {
        $ties = games::totalTies($this->games(), $this->rank);

        $this->ties = $ties;
        $this->save();
    }

    public function recordScores()
    {
        $this->recordWins();
        $this->recordLoses();
        $this->recordTies();
    }

    public function calculateRank()
    {
        $this->recordScores();
        $this->recordEndRank();
    }

    public function recordEndRank()
    {
        $roundResults = self::where('rounds_id', $this->rounds_id)
        ->where('tier', $this->tier)
        ->where('league_id', $this->league_id)
        ->orderBy('wins', 'DESC')
        ->orderBy('rank')
        ->get();
        $count = 1;
        foreach ($roundResults as $result) {
            $result->end_rank = $count;
            $result->save();
            $count++;
        }
    }

    public function calculateNextRoundResults()
    {
        $newTier = $this->_newTier();
            
        $newRank = $this->_getNewRank();

        $nextRound = $this->league->nextRound($this->rounds);
        
        if ($updateResult = $this->_resultExists($newTier, $newRank, $nextRound)) {
            $updateResult->team_id = $this->team_id;
            $updateResult->save();
        } else {
            dd('updateResult not there?');
            $newResult = new self;
            $newResult->rounds_id = $nextRound->id;
            $newResult->team_id = $this->team_id;
            $newResult->rank = $newRank;
            $newResult->tier = $newTier;
            $newResult->league_id = $this->league_id;
            $newResult->save();
        }
    }

    private function _resultExists($tier, $rank, $nextRound)
    {
        $where = ['tier' => $tier, 'rank' => $rank, 
                'league_id' => $this->league_id,
                'rounds_id' => $nextRound->id];
        return $this->where($where)->first();
    }

    private function _newTier()
    {
        switch ($this->end_rank) {
            case 1:
            case 2:
            case 3:
                $newTier = $this->tier - 1;
                break;
            case 4:
            case 5:
                $newTier = $this->tier;
                break;
            case 6:
            case 7:
            case 8:
                $newTier = $this->tier + 1;
                break;
        }
        if ($newTier < 1) {
            $newTier = 1;
        } else if ($newTier > team::lowestTier($this->league_id)) {
            $newTier = $this->tier;
        }

        return $newTier;
    }

    private function _getNewRank()
    {
        //if highest tier 1,2,3,4,5 stay the same
        //if lowest tier 4,5,6,7,8 stay the same
        switch ($this->end_rank) {
            case 1:
                if ($this->tier == 1) {
                    return 1;
                } else {
                    return 6;
                }
            case 2:
                if ($this->tier == 1) {
                    return 2;
                } else {
                    return 7;
                }
            case 3:
                if ($this->tier == 1) {
                    return 3;
                } else {
                    return 8;
                }
            case 4:
                return 4;
            case 5:
                return 5;
            case 6:
                if ($this->tier == team::lowestTier($this->league_id)) {
                    return 6;
                } else {
                    return 1;
                }
            case 7:
                if ($this->tier == team::lowestTier($this->league_id)) {
                    return 7;
                } else {
                    return 2;
                }
            case 8:
            if ($this->tier == team::lowestTier($this->league_id)) {
                return 8;
            } else {
                return 3;
            }
        }
    }
}
