@extends('layouts.app')

@section('content')

@foreach($roundResultsByTier as $tier => $teamResults)
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <div class="h3">
                        Tier {{ $tier }}
                    </div>
                </div>
                <div class="panel-body">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Team #</th>
                            <th>Team Name</th>
                            <th>Team Rep</th>
                            <th>Phone Number</th>
                            <th>Email Address</th>
                        <tr>
                    </thead>
                    <tbody>
                        @foreach ($teamResults as $rank)
                            <tr>
                                <td>{{ $rank->rank }}</td>
                                <td><a href="{{ route('team', ['id' => $rank->team->id]) }}">{{ $rank->team->team_name }}</a></td>
                                <td>{{ $rank->team->contact_name }}</td>
                                <td>{{ $rank->team->contact_phone }}</td>
                                <td>{{ $rank->team->contact_email }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endforeach

@endsection