@extends('layouts.app')

@section('content')
    <div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                Tier Results
                </div>
                <div class="panel-body">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Rank</th>
                                <th>Names</th>
                                @foreach ($teamNames as $rank => $teamName)
                                    <th>{{$rank}}</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                        @foreach ($teamNames as $rank => $teamName)
                            <tr>
                                <td>{{$rank}}</td>
                                <td>{{$teamName}}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    </div>

@endsection