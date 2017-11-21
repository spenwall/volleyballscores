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
                                @foreach ($results as $result)
                                <th>{{ $result->rank }}</th>
                                @endforeach
                                <th>Wins</th>
                                <th>loses</th>
                                <th>Ties</th>
                                <th>Ending Rank</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($results as $result)
                                <tr>
                                    <td>{{ $result->rank }}</td>
                                    <td>{{ $result->team->team_name }}</td>
                                    @foreach ($results as $opponents)
                                    <td>{{ $opponents->rank }}</td>
                                    @endforeach
                                    <td>{{ $result->wins }}</td>
                                    <td>{{ $result->loses }}</td>
                                    <td>{{ $result->ties }}</td>
                                    <td>{{ $result->end_rank }}</td>
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