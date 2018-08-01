@extends('layouts.app')

@section('content')
<style>
    .content {
        text-align: center;
    }

    .title {
        font-size: 84px;
        color: #636b6f;
        font-family: 'Raleway', sans-serif;
        font-weight: 100;
        margin-top: 50px;
    }
</style>
<div class="content">
    <div class="title">
        {{ config('app.name', 'AviaionToolbox') }}
    </div>
</div>
@endsection
