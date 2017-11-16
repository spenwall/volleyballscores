@extends('layouts.app')

@section('content')

<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <ul class="nav nav-tabs">
                @foreach($tiers as $key => $tier)
                <li role="presentation" class="{{ $key == 0 ? "active" : "" }}"><a href="#">Tier {{ $tier->tier }}</a></li>
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
                        Tier {{ $tiers[0]->tier }} Schedule
                    </div>
                </div>
                <div class="panel-body">
                    <table class="table table-bordered table-responsive">
                            <tr>
                                <th rowspan="2">Date</th>
                                @foreach($courts as $location => $allCourts)
                                <th colspan="{{ count($allCourts) }}">{{ $location }}</th>
                                @endforeach
                            </tr>
                            <tr>
                            @foreach($courts as $location => $court)
                                @foreach ($court as $courtNumber => $games)
                                    <?php xdebug_break(); ?>
                                    <td>{{ $courtNumber }}</td>
                                @endforeach
                            @endforeach
                            </tr>
                            <tr>
                                
                            </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection