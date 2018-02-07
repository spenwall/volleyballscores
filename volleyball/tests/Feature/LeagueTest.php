<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\League;
use App\Team;

class LeagueTest extends TestCase
{
    /** @test */
    public function at_least_one_league_exsits()
    {
        $league = League::leagues();
        
        $this->assertNotEmpty($league);

    }

}
