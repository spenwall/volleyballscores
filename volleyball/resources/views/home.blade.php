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
                        <div class="game-date">
                            {{$game['date']}}
                        </div>
                        <div class="btn-group">
                            <input type="hidden" name="game" value="{{$game['id']}}" />
                            <input type="radio" name="winner" value="{{$game['team1']}}" {{ $game['winner'] == $game['team1'] ? "checked='checked'" : ""}}>{{$game['team1_rank']}} {{$game['team1_name']}}<br />
                            vs</br>
                            <input type="radio" name="winner" value="{{$game['team2']}}" {{ $game['winner'] == $game['team2'] ? "checked='checked'" : ""}}>{{$game['team2_rank']}} {{$game['team2_name']}}<br />
                        </div>
                        </br>
                        <input type="submit" value="Submit"><div class="score-recorded">{{ $gameUpdated == $game['id'] ? 'Success' : ''}}</div>
                        </form>
                    @endforeach

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
