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
                    <table class="table table-striped table-responsive8">
                        <thead>
                            <tr>
                                <th>Rank</th>
                                <th>Team</th>
                                @foreach ($results as $teamResults)
                                <th>{{ $teamResults->rank }}</th>
                                @endforeach
                                <th style="text-align: center">Wins</th>
                                <th style="text-align: center">loses</th>
                                <th style="text-align: center">Ties</th>
                                <th style="text-align: center">Ending Rank</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($results as $teamResults)
                            <?php $games = $teamResults->games(); ?>
                                <tr>
                                    <td>{{ $teamResults->rank }}</td>
                                    <td>{{ $teamResults->team->team_name }}</td>
                                    @foreach ($results as $opponent)
                                        <?php $winner = App\games::winner($games, $teamResults->rank, $opponent->rank); ?>
                                        @if ($winner == '-')
                                        <td bgcolor="#ABC1C5"></td>
                                        @else
                                        <td>{{ $winner }}</td>
                                        @endif
                                    @endforeach
                                    <td align="center">{{ App\games::totalWins($games, $teamResults->rank) }}</td>
                                    <td align="center">{{ App\games::totalLoses($games, $teamResults->rank) }}</td>
                                    <td align="center">{{ App\games::totalTies($games, $teamResults->rank) }}</td>
                                    <td align="center">{{ $teamResults->end_rank }}</td>
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