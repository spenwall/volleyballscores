@extends('layouts.app')

@section('content')

<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <ul class="nav nav-tabs">
                @foreach($tiers as $key => $tier)
                <li role="presentation" class="{{ $key == 0 ? "active" : "" }}">
                    <a href="#">Tier {{ $tier }}</a>
                </li>
                @endforeach
            </ul>
        </div>  
    </div>
</div>


<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <div class="team h3">
                        Tier {{ $tiers[0] }} Schedule
                    </div>
                </div>
                <div class="panel-body">
                    <table class="table table-bordered table-responsive">
                            <tr>
                                <th rowspan="2">Date</th>
                                @foreach($games as $round_id => $round_games)
                                    <?php $locations = $round_games->groupBy('location'); ?>
                                    <?php dd($locations); ?>
                                    @foreach ($locations as $location => $games)
                                        <th colspan="{{ count($locations) }}">{{ $location }}</th>
                                    @endforeach
                                @endforeach
                            </tr>
                            <tr>
                           </tr>
                            <tr>
                                <td rowspan="2">Date</td>
                                <td>Game</td>
                                <td>game</td>
                                <td>game</td>
                                <td>game</td>
                            </tr>
                            <tr>
                                <td>game</td>
                                <td>game</td>
                                <td>game</td>
                                <td>game</td>
                            <tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection