<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class roundResults extends Model
{
    public function team()
    {
        return $this->belongsTo('team');
    }
}
