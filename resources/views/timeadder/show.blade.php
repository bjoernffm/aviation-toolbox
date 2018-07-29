@extends('layouts.app')

@section('content')
<div class="row" ng-controller="TimeController as TimeCtrl">
    <div class="col-sm-6">
        <table border="0" class="table">
            <thead>
                <tr>
                    <th>Stunden</th>
                    <th>Minuten</th>
                    <th></th>
                <tr>
            </thead>
            <tbody>
                <tr>
                    <td>@{{TimeCtrl.total.hours}}</td>
                    <td>@{{TimeCtrl.total.minutes}}</td>
                    <td></td>
                </tr>
                <tr ng-repeat="item in TimeCtrl.times">
                    <td><input type="text" ng-model="item.hours" ng-change="TimeCtrl.calc()" /></td>
                    <td><input type="text" ng-model="item.minutes" ng-change="TimeCtrl.calc()" /></td>
                    <td></td>
                </tr>
            <tbody>
        </table>
    </div>
    <div class="col-sm-6">
        <pre>@{{TimeCtrl.editFields|json}}</pre>
        <table border="0" class="table">
            <thead>
                <tr>
                    <th>Stunden</th>
                    <th>Minuten</th>
                    <th></th>
                <tr>
            </thead>
            <tbody>
                <tr>
                    <td><input type="text" class="form-control" id="hourNumber" ng-keydown="TimeCtrl.keyDownHourTrigger($event)" ng-model="TimeCtrl.editFields.hours" /></td>
                    <td><input type="text" class="form-control" id="minuteNumber" ng-keydown="TimeCtrl.keyDownMinuteTrigger($event)" ng-model="TimeCtrl.editFields.minutes" /></td>
                    <td><button class="btn btn-primary" ng-click="TimeCtrl.add()"><i class="fas fa-plus-circle"></i></button></td>
                </tr>
            <tbody>
        </table>
    </div>
</div>
@endsection

@section('javascript')

@endsection