@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-sm-3"></div>
    <div class="col-sm-6">
        <div class="jumbotron">
            <h1 class="display-4">Airport Directory</h1>
            <form action="{{action('AirportController@store')}}" method="post">
                {{csrf_field()}}
                <div class="form-group">
                    <label for="identifier">Airport ICAO Code</label>
                    <input value="{{ old('identifier') }}" id="identifier" name="identifier" type="text" class="form-control" />
                </div>
                <div class="text-center">
                <button type="submit" class="btn btn-primary btn-lg">Get Aiport Details</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection