@extends('layouts.app')

@section('content')
@php
    use bjoernffm\e6b\Calculator as e6bCalc;
@endphp
<div class="row">
    <div class="col-sm-8">
        <h1>{{$airport->ident}}</h1>
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
    <div class="col-sm-4">
        <h4>Details</h4>
        <dl>
            <dt>Elevation</dt>
            <dd>{{$airport->elevation_ft}} ft</dd>
            <dt>Municipality</dt>
            <dd>{{$airport->municipality}}</dd>
        </dl>
    </div>
    <div class="col-sm-4">
        <h4>Frequencies</h4>
        <dl>
        @foreach ($airport->frequencies as $frequency)
            <dt>{{$frequency->type}} - {{$frequency->description}}</dt>
            <dd>{{$frequency->frequency_mhz}} MHz</dd>
        @endforeach
        </dl>
    </div>
    <div class="col-sm-4">
        <h4>Runways</h4>
        <dl>
        @foreach ($airport->runways as $runway)
            <dt>{{$runway->le_ident}} / {{$runway->he_ident}} - {{$runway->surface}}</dt>
            <dd>Length: {{$runway->length_ft}} ft / {{e6bCalc::convertFeet($runway->length_ft)['meters']}} m</dd>
        @endforeach
        </dl>
    </div>
</div>
<hr />
    <style>
      /* Always set the map height explicitly to define the size of the div
       * element that contains the map. */
      #map {
        height: 400px;
      }
    </style>
    <div id="map"></div>
    <script>
      var map;
      function initMap() {
        map = new google.maps.Map(document.getElementById('map'), {
          center: {lat: {{$airport->latitude_deg}}, lng: {{$airport->longitude_deg}}},
          zoom: 14,
            mapTypeId: 'satellite'
        });
      }
    </script>
    <script src="https://maps.googleapis.com/maps/api/js?key=&callback=initMap"
    async defer></script>
  </body>
</html>
@endsection