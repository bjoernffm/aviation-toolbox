angular.module('TimeApp', [])
.controller('TimeController', ['$scope', function($scope) {
    var self = this;

    self.editFields = {
        minutes: '',
        minuteState: 0,
        hours: '',
        hourState: 0
    };
    self.total = {
        minutes: '0',
        hours: '0'
    };

    self.times = [];

    self.add = function() {
        if (isNaN(parseFloat(self.editFields.hours)) || isNaN(parseFloat(self.editFields.minutes))) {
            return;
        }
        if (parseFloat(self.editFields.hours) == 0 && parseFloat(self.editFields.minutes) == 0) {
            return;
        }
        self.times.push(angular.copy(self.editFields));
        self.calc();

        self.editFields = {
            minutes: '',
            minuteState: 0,
            hours: '',
            hourState: 0
        };
        document.getElementById('hourNumber').focus();
    };

    self.calc = function() {
        hours = 0;
        minutes = 0;

        for(var i = 0; i < self.times.length; i++) {
            if (isNaN(parseFloat(self.times[i].hours))) {
                self.times[i].hours = 0;
            }
            if (isNaN(parseFloat(self.times[i].minutes))) {
                self.times[i].minutes = 0;
            }

            self.times[i].hours = parseInt(self.times[i].hours);
            self.times[i].minutes = parseInt(self.times[i].minutes);

            hours += self.times[i].hours;
            minutes += self.times[i].minutes;
        }

        hours += Math.floor(minutes/60);
        minutes = minutes % 60;

        self.total = {
            minutes: minutes,
            hours: hours
        };
    }

    self.keyDownHourTrigger = function(event) {
        event.preventDefault();

        if (self.editFields.hourState == 0) {
            // when nothing is entered yet

            if (event.keyCode == 13) {
                // enter
                self.editFields.hours = '0';
                document.getElementById('minuteNumber').focus();
                self.editFields.hourState = 2;

                if (self.editFields.minutes != null) {
                    self.add();
                } else {
                    document.getElementById('minuteNumber').focus();
                }
            } else if (event.keyCode == 48 || event.keyCode == 9) {
                // 0, tab
                self.editFields.hours = '0';
                document.getElementById('minuteNumber').focus();
                self.editFields.hourState = 2;
            } else if (event.keyCode == 49 || event.keyCode == 50 || event.keyCode == 51 || event.keyCode == 52 || event.keyCode == 53 || event.keyCode == 54 || event.keyCode == 55 || event.keyCode == 56 || event.keyCode == 57) {
                // 1..9
                self.editFields.hours = self.editFields.hours + event.key;
                self.editFields.hourState = 1;
            }
        } else if (self.editFields.hourState == 1) {
            // when the hours are filled with a number

            if (event.keyCode == 13) { // enter
                self.editFields.minutes = 0;
                self.add();
            } else if (event.keyCode == 8) { // backspace
                self.editFields.hours = self.editFields.hours.substring(0, self.editFields.hours.length - 1);
                if (self.editFields.hours.length == 0) {
                    self.editFields.hourState = 0;
                }
            } else if (event.keyCode == 9) { // tab
                document.getElementById('minuteNumber').focus();
            } else if (event.keyCode == 48 || event.keyCode == 49 || event.keyCode == 50 || event.keyCode == 51 || event.keyCode == 52 || event.keyCode == 53 || event.keyCode == 54 || event.keyCode == 55 || event.keyCode == 56 || event.keyCode == 57) { // 0..9
                self.editFields.hours = self.editFields.hours + event.key;
            }
        } else if (self.editFields.hourState == 2) {
            // when hours are filled with a zero

            if (event.keyCode == 13) { // enter
                self.editFields.hours = '0';
                document.getElementById('minuteNumber').focus();
                self.editFields.hourState = 2;

                if (self.editFields.minutes != "") {
                    self.add();
                } else {
                    document.getElementById('minuteNumber').focus();
                }
            } else if (event.keyCode == 8) { // backspace
                self.editFields.hours = '';
                self.editFields.hourState = 0;
            } else if (event.keyCode == 9) { // tab
                document.getElementById('minuteNumber').focus();
            }
        }
    }

    self.keyDownMinuteTrigger = function(event) {
        event.preventDefault();

        if (self.editFields.minuteState == 0) {
            // when nothing is entered yet

            if (event.keyCode == 13 || event.keyCode == 48 || event.keyCode == 9) {
                // enter, 0, tab

                self.editFields.minutes = '0';

                if (self.editFields.hours != "") {
                    self.add();
                    self.editFields.hours = "";
                    self.editFields.hourState = 0;
                    self.editFields.minutes = "";
                    self.editFields.minuteState = 0;
                }

                document.getElementById('hourNumber').focus();
            } else if (event.keyCode == 49 || event.keyCode == 50 || event.keyCode == 51 || event.keyCode == 52 || event.keyCode == 53) {
                // 1..9
                self.editFields.minutes = self.editFields.minutes + event.key;
                self.editFields.minuteState = 1;
            }
        } else if (self.editFields.minuteState == 1) {
            // when first digit is entered

            if (event.keyCode == 13 || event.keyCode == 9) {
                // enter, tab

                if (self.editFields.hours != "") {
                    self.add();
                    self.editFields.hours = "";
                    self.editFields.hourState = 0;
                    self.editFields.minutes = "";
                    self.editFields.minuteState = 0;
                }

                document.getElementById('hourNumber').focus();
            } else if (event.keyCode == 8) {
                // backspace

                self.editFields.minutes = self.editFields.minutes.substring(0, self.editFields.minutes.length - 1);
                if (self.editFields.minutes.length == 0) {
                    self.editFields.minuteState = 0;
                }
            } else if (event.keyCode == 48 || event.keyCode == 49 || event.keyCode == 50 || event.keyCode == 51 || event.keyCode == 52 || event.keyCode == 53 || event.keyCode == 54 || event.keyCode == 55 || event.keyCode == 56 || event.keyCode == 57) {
                // 1..9

                self.editFields.minutes = self.editFields.minutes + event.key;
                self.editFields.minuteState = 1;

                if (self.editFields.hours != "") {
                    self.add();
                    self.editFields.hours = "";
                    self.editFields.hourState = 0;
                    self.editFields.minutes = "";
                    self.editFields.minuteState = 0;
                }

                document.getElementById('hourNumber').focus();
            }
        }
    }

    document.getElementById('hourNumber').focus();
}]);