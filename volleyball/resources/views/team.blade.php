@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <div class="h3">
                        {{ $team->team_name }} #{{ $team->rank }}
                    </div>
                </div>
                <div class="panel-body">
                    <div class="h3">
                        Team Contact
                    </div>
                    <div><span>Name:</span> {{ $team->contact_name }}</div>
                    <div><span>Phone:</span> {{ $team->contact_phone }}</div>
                    <div><span>Email:</span><a href="mailto:{{$team->contact_email}}"> {{ $team->contact_email }}</a></div>

                    <div class="h3">
                        Roster
                    </div>
                    @foreach ($team->players as $player)
                    <div>{{ $player->player_name }}</div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>


@endsection