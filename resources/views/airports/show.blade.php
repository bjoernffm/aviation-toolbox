@extends('layouts.app')

@section('content')
@php
    use bjoernffm\e6b\Calculator as e6bCalc;
    $coordinates = e6bCalc::convertCoordinates($airport->latitude_deg.' '.$airport->longitude_deg);
@endphp
<div class="row">
    <div class="col-sm-8">
        <h1>
            {{$airport->ident}}
            @if ($airport->type == 'closed')
            <span class="badge badge-danger">CLOSED</span>
            @endif
        </h1>
        <h3>{{$airport->name}}</h3>
    </div>
    <div class="col-sm-4">
        <form action="{{action('AirportController@store')}}" method="post">
            {{csrf_field()}}
            <input id="identifier" name="identifier" placeholder="Search another Aiport" type="text" class="form-control" />
        </form>
    </div>
</div>
<hr />
<div class="row">
    <div class="col-sm-6">
        <div class="row">
            <div class="col-sm-12">
                <h4>
                    Metar
                    @if ($metar->flight_category == 'VFR')
                    <span class="badge badge-success">VFR</span>
                    @elseif ($metar->flight_category == 'MVFR')
                    <span class="badge badge-primary">MVFR</span>
                    @elseif ($metar->flight_category == 'IFR')
                    <span class="badge badge-danger">IFR</span>
                    @elseif ($metar->flight_category == 'LIFR')
                    <span class="badge badge-success" style="background-color: #FF00FF;">LIFR</span>
                    @endif
                </h4>
                {{$metar->raw_text}}
            </div>
        </div>
        <hr />
        <div class="row">
            <div class="col-sm-6">
                <h4>Details</h4>
                <dl>
                    <dt>Elevation</dt>
                    <dd>{{$airport->elevation_ft}} ft</dd>
                    <dt>Municipality</dt>
                    <dd>{{$airport->municipality}}</dd>
                    <dt>Coordinates</dt>
                    <dd>
                        {{$coordinates['dms']}}<br />
                        {{$coordinates['ddm']}}<br />
                        {{$coordinates['dds']}}
                    </dd>
                </dl>

                <h4>Runways</h4>
                <dl>
                @foreach ($airport->runways as $runway)
                    <dt>
                        @if ($runway->closed == '1')
                        <span class="badge badge-danger">CLOSED</span>
                        @endif

                        {{$runway->le_ident}} / {{$runway->he_ident}}
                        @if ($runway->surface == 'ASP' or $runway->surface == 'ASPH')
                        <span class="badge badge-dark">{{$runway->surface}}</span>
                        @elseif ($runway->surface == 'CON' or $runway->surface == 'CONC')
                        <span class="badge badge-secondary">{{$runway->surface}}</span>
                        @elseif ($runway->surface == 'GRS')
                        <span class="badge badge-success">{{$runway->surface}}</span>
                        @else
                        <span class="badge badge-light">{{$runway->surface}}</span>
                        @endif
                    </dt>
                    <dd>Length: {{$runway->length_ft}} ft / {{e6bCalc::convertFeet($runway->length_ft)['meters']}} m</dd>
                @endforeach
                </dl>
            </div>
            <div class="col-sm-6">
                <h4>Sun Info</h4>
                <dl>
                    <dt>BCMT</dt>
                    <dd>
                        {{$sunInfo['bcmt']['utc']->format('H:i')}} UTC
                        @if (isset($sunInfo['bcmt']['local']))
                        ({{$sunInfo['bcmt']['local']->format('H:i')}} LT)
                        @endif
                    </dd>
                    <dt>ECET</dt>
                    <dd>
                        {{$sunInfo['ecet']['utc']->format('H:i')}} UTC
                        @if (isset($sunInfo['bcmt']['local']))
                        ({{$sunInfo['ecet']['local']->format('H:i')}} LT)
                        @endif
                    </dd>
                </dl>
                <h4>Frequencies</h4>
                <dl>
                @foreach ($airport->frequencies as $frequency)
                    <dt>{{$frequency->type}} - {{$frequency->description}}</dt>
                    <dd>{{number_format($frequency->frequency_mhz, 3)}} MHz</dd>
                @endforeach
                </dl>
            </div>
        </div>
    </div>
    <div class="col-sm-6">
    <div id="map" style="height: 400px;"></div>
    </div>
</div>
<script>
    var map;
    function initMap() {
        map = new google.maps.Map(document.getElementById('map'), {
            center: {
                lat: {{$airport->latitude_deg}},
                lng: {{$airport->longitude_deg}}
            },
            zoom: 14,
            mapTypeId: 'satellite'
        });
    }
</script>
<script src="https://maps.googleapis.com/maps/api/js?key=&callback=initMap"
async defer></script>
@endsection