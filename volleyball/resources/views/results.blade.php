@extends('layouts.app')

@section('content')

    @if ($resultsByTier)
    @foreach ($resultsByTier as $tier => $results)
    <div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                Tier {{ $tier }} Results
                </div>
                <div class="panel-body">
                    <table class="table table-striped table-responsive">
                        <thead>
                            <tr>
                                <th>Rank</th>
                                <th>Team</th>
                                @foreach ($teamsByTiers[$tier] as $teamByRound)
                                <th>{{ $teamByRound->rank }}</th>
                                @endforeach
                                <th>Wins</th>
                                <th>loses</th>
                                <th>Ties</th>
                                <th>Ending Rank</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($teamsByTiers[$tier] as $team)
                                <tr>
                                    <td>{{ $team->rank }}</td>
                                    <td>{{ $team->team_name }}</td>
                                    @foreach ($teamsByTiers[$tier] as $opponent)
                                    <td>{{ $results[$team->rank][$opponent->rank] }}
                                    @endforeach
                                    <td>{{ $team->wins }}</td>
                                    <td>{{ $team->loses }}</td>
                                    <td>{{ $team->ties }}</td>
                                    <td>Rank</td>
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
    @else
    <div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                No Results
                </div>
            </div>
        </div>
    </div>
    </div>
    @endif
@endsection