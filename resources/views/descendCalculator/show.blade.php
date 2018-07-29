@extends('layouts.app')

@section('content')
@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<h1>Descend Calculator</h1>

<hr />

<div class="row autobrake">
    <div class="col-sm-6">
        <form action="{{action('DescendCalculatorController@store')}}" method="post">
            {{csrf_field()}}
            <div class="row">
                <div class="col-sm-6">
                    <div class="form-group">
                        <label for="ias">Indicated Airspeed (kts)*</label>
                        <input value="{{ old('ias') }}" id="ias" name="ias" type="number" class="form-control" />
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <label for="descentRate">Descent Rate (ft/min)*</label>
                        <input value="{{ old('descentRate') }}" id="descentRate" name="descentRate" type="number" class="form-control" />
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-6">
                    <div class="form-group">
                        <label for="initialAltitude">Initial Altitude (ft)*</label>
                        <input value="{{ old('initialAltitude') }}" id="ias" name="initialAltitude" type="number" class="form-control" />
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <label for="targetAltitude">Target Altitude (ft)*</label>
                        <input value="{{ old('descentRate') }}" id="targetAltitude" name="targetAltitude" type="number" class="form-control" />
                    </div>
                </div>
            </div>
            <small class="form-text text-muted">
                * required
            </small>
            <br />
            <button type="submit" class="btn btn-default">Calculate</button>
        </form>
    </div>
    <div class="col-sm-6">
        @if ($calculation != null)
            <div id="container" style="height: 400px;" data-highcharts-chart="0"></div>
        @else
            <div class="jumbotron">
                <h1 class="display-4">Almost there!</h1>
                <p class="lead">Use the form and fill in all necessary information to get the wanted output.</p>
            </div>
        @endif
    </div>
</div>
<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/modules/series-label.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>
@endsection

@section('javascript')
@if ($calculation != null)
Highcharts.chart('container', {
    chart: {
        type: 'spline',
        backgroundColor:'transparent'
    },
    title: {
        text: 'Descend Path'
    },
    subtitle: {
        text: 'Reaching EOD after {{round($calculation['nauticalMiles'])}}nm / {{round($calculation['minutes'])}}min'
    },
    xAxis: {
        title: {
            text: 'Distance (nm)'
        }
    },
    yAxis: {
        title: {
            text: 'Altitude (ft)'
        },
        min: 0,
        plotLines: [{
            value: {{round($calculation['path'][0]['altitude'])}},
            color: 'blue',
            width: 1,
            label: {
                text: 'TOD',
                align: 'center',
                style: {
                    color: 'gray'
                }
            }
        }, {
            value: 10000,
            color: 'black',
            width: 1,
            label: {
                text: '10.000 ft',
                align: 'left',
                style: {
                    color: 'gray'
                }
            }
        }, {
            value: {{round($calculation['path'][count($calculation['path'])-1]['altitude'])}},
            color: 'blue',
            width: 1,
            label: {
                text: 'EOD',
                align: 'center',
                style: {
                    color: 'gray'
                }
            }
        }]
    },
    tooltip: {
        headerFormat: '<b>{series.name}</b><br>',
        pointFormat: '{point.x:.0f}nm: {point.y:.0f}ft'
    },

    plotOptions: {
        spline: {
            marker: {
                enabled: true
            }
        }
    },

    // Define the data points. All series have a dummy year
    // of 1970/71 in order to be compared on the same x axis. Note
    // that in JavaScript, months start at 0 for January, 1 for February etc.
    series: [{
        name: " ",
          data: @json($graphData),
        marker: {
            enabled: false
        },
    }]
});
@endif
@endsection