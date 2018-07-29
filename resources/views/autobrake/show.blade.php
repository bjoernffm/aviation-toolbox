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

<h1>Boeing 738 NG Autobrake Calculator</h1>

<hr />

<div class="row autobrake">
    <div class="col-sm-6">
        <form action="{{action('AutobrakeController@store')}}" method="post">
            {{csrf_field()}}
            <div class="form-group">
                <label for="wtInKgs">Weight (Kgs)*</label>
                <input value="{{ old('wtInKgs') }}" id="wtInKgs" name="wtInKgs" type="number" class="form-control" />
            </div>
            <div class="row">
                <div class="col-sm-6">
                    <div class="form-group">
                        <label for="lda">Landing distance available*</label>
                        <input value="{{ old('lda') }}" id="lda" name="lda" type="number" class="form-control" />
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <label for="rwyCondition">Runway Condition*</label>
                        <select id="rwyCondition" name="rwyCondition" class="form-control">
                            <option value="dry">Dry</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-6">
                    <div class="form-group">
                        <label for="airportElevationInFt">Airport elevation (Ft)</label>
                        <input value="{{ old('airportElevationInFt') }}" id="airportElevationInFt" name="airportElevationInFt" placeholder="0" type="number" class="form-control" />
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <label for="airportTemperatureInDeg">Airport Temperature (°C)</label>
                        <input value="{{ old('airportTemperatureInDeg') }}" id="airportTemperatureInDeg" name="airportTemperatureInDeg" placeholder="15" type="number" class="form-control" />
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-6">
                    <div class="form-group">
                        <label for="windInKts">Wind (Kts)</label>
                        <input value="{{ old('windInKts') }}" id="windInKts" name="windInKts" placeholder="0" type="number" class="form-control" />
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <label for="windDirection">&nbsp;</label>
                        <select id="windDirection" name="windDirection" class="form-control">
                            <option value="head">HEAD</option>
                            <option value="tail">TAIL</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-6">
                    <div class="form-group">
                        <label for="slopeInDeg">Runway Slope (°)</label>
                        <input value="{{ old('slopeInDeg') }}" id="slopeInDeg" name="slopeInDeg" placeholder="0" type="number" class="form-control" />
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <label for="slopeDirection">&nbsp;</label>
                        <select id="slopeDirection" name="slopeDirection" class="form-control">
                            <option value="up">UP</option>
                            <option value="down">DOWN</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-6">
                    <div class="form-group">
                        <label for="spdAbvVref">Speed above V<suB>REF</sub></label>
                        <input value="{{ old('spdAbvVref') }}" id="spdAbvVref" name="spdAbvVref" placeholder="0" type="number" class="form-control" />
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <label for="reverseThrust">Reverse Thrust*</label>
                        <select id="reverseThrust" name="reverseThrust" class="form-control">
                            <option value="normal">NORMAL</option>
                            <option value="one_rev">ONE REVERSER</option>
                            <option value="no_rev">NO REVERSER</option>
                        </select>
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
        @if ($calculations != null)
            <div class="distanceBox" style="background-color: #333; margin: 5px 0;">
                <h1 style="color: #fff; padding:10px 20px; margin: 0;">RUNWAY - {{@round($calculations['runway']['total_dist'])}}m</h2>
            </div>
            @if ($calculations['max_manual']['lda_miss'])
                <div class="distanceBox miss" style="margin: 5px 0;">
                <h2>MISS - MAX MANUAL - {{@round($calculations['max_manual']['total_dist'])}}m</h2>
            @else
                <div class="distanceBox" style="margin: 5px 0;">
                <h2>MAX MANUAL - {{@round($calculations['max_manual']['total_dist'])}}m</h2>
            @endif
                <div style="background-color: #55e; width: {{$calculations['max_manual']['lda_used']}}%;"></div>
            </div>
            @if ($calculations['autobrake_max']['lda_miss'])
                <div class="distanceBox miss">
                <h2>MISS - AUTOBRAKE MAX - {{@round($calculations['autobrake_max']['total_dist'])}}m</h2>
            @else
                <div class="distanceBox">
                <h2>AUTOBRAKE MAX - {{@round($calculations['autobrake_max']['total_dist'])}}m</h2>
            @endif
                <div style="background-color: #2b2; width: {{$calculations['autobrake_max']['lda_used']}}%;"></div>
            </div>
            @if ($calculations['autobrake_3']['lda_miss'])
                <div class="distanceBox miss">
                <h2>MISS - AUTOBRAKE 3 - {{@round($calculations['autobrake_3']['total_dist'])}}m</h2>
            @else
                <div class="distanceBox">
                <h2>AUTOBRAKE 3 - {{@round($calculations['autobrake_3']['total_dist'])}}m</h2>
            @endif
                <div style="background-color: #3c3; width: {{$calculations['autobrake_3']['lda_used']}}%;"></div>
            </div>

            @if ($calculations['autobrake_2']['lda_miss'])
                <div class="distanceBox miss">
                <h2>MISS - AUTOBRAKE 2 - {{@round($calculations['autobrake_2']['total_dist'])}}m</h2>
            @else
                <div class="distanceBox">
                <h2>AUTOBRAKE 2 - {{@round($calculations['autobrake_2']['total_dist'])}}m</h2>
            @endif
                <div style="background-color: #4d4; width: {{$calculations['autobrake_2']['lda_used']}}%;"></div>
            </div>

            @if ($calculations['autobrake_1']['lda_miss'])
                <div class="distanceBox miss">
                <h2>MISS - AUTOBRAKE 1 - {{@round($calculations['autobrake_1']['total_dist'])}}m</h2>
            @else
                <div class="distanceBox">
                <h2>AUTOBRAKE 1 - {{@round($calculations['autobrake_1']['total_dist'])}}m</h2>
            @endif
                <div style="background-color: #5e5; width: {{$calculations['autobrake_1']['lda_used']}}%;"></div>
            </div>
        @else
            <div class="jumbotron">
                <h1 class="display-4">Almost there!</h1>
                <p class="lead">Use the form and fill in all necessary information to get the wanted output.</p>
            </div>
        @endif
    </div>
</div>
@endsection

@section('javascript')

@endsection