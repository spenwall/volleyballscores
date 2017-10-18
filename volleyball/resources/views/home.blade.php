@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">{{$team->team_name}}</div>

                <div class="panel-body">
                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif

                    Welcome please select the winner of the game and hit submit.

                    @foreach($games as $game)
                    <form id="game{{$game['id']}}" method="post" action="/home">
                    {{ csrf_field() }}
                    <div class="full">
                        <div class="col-md-4">
                            <div class="game-date">
                                {{$game['date']}}
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="btn-group">
                                <input type="hidden" name="game" value="{{$game['id']}}" />
                                <input type="radio" name="winner" value="{{$game['team1']}}" {{ $game['winner'] == $game['team1'] ? "checked='checked'" : ""}}> {{$game['team1_rank']}} {{$game['team1_name']}}<br />
                                <input type="radio" name="winner" value="{{$game['team2']}}" {{ $game['winner'] == $game['team2'] ? "checked='checked'" : ""}}> {{$game['team2_rank']}} {{$game['team2_name']}}<br />
                                <input type="radio" name="winner" value="0"> Tie
                            </div>
                        </div>
                        <div class="col-md-4">
                            <input type="submit" value="Submit"><div class="score-recorded">{{ isset($gameUpdated) && ($gameUpdated == $game['id']) ? 'Success' : ''}}</div>
                        </div>
                        <div class="col-md-12">
                            <hr>
                        </div>
                    </div>
                    </form>
                    
                    @endforeach

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
