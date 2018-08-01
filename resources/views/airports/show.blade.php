@extends('layouts.app')

@section('content')
@php
    use bjoernffm\e6b\Calculator as e6bCalc;
@endphp
<h1>{{$airport->ident}}</h1>
<h3>{{$airport->name}}</h3>
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
@endsection