@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <div class="team h3">
                        {{ $team->team_name }}
                    </div>
                    <div class="round h4">
                       Round {{ $round }}
                    </div>
                </div>
                <div class="panel-body">
                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif

                    Please select the winner of the game and hit submit to record your scores.

                    @foreach($games as $game)
                    <form id="game{{$game['id']}}" method="post" action="/home">
                    {{ csrf_field() }}
                    <div class="games">
                        <div class="col-md-4">
                            <div class="game-date">
                                {{$game['date']}}
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="btn-group">
                                <input type="hidden" name="game" value="{{$game['id']}}" />
                                <input type="radio" name="winner" value="{{$game->team1()->currentRank()}}" {{ $game['winner'] == $game->team1()->currentRank() ? "checked='checked'" : ""}}> {{$game->team1()->currentRank()}} {{$game->team1()->team_name}}<br />
                                <input type="radio" name="winner" value="{{$game->team2()->currentRank()}}" {{ $game['winner'] == $game->team2()->currentRank() ? "checked='checked'" : ""}}> {{$game->team2()->currentRank()}} {{$game->team2()->team_name}}<br />
                                <input type="radio" name="winner" value="0" {{ $game['winner'] == 0 ? "checked='checked'" : "" }}> Tie
                            </div>
                        </div>
                        <div class="col-md-4">
                            <input type="submit" value="Submit" class="btn btn-primary"><div class="score-recorded">{{ isset($gameUpdated) && ($gameUpdated == $game['id']) ? 'Success' : ''}}</div>
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
