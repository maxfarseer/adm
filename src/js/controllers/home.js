'use strict';
/**
 * @ngInject
 */
function homeCtrl($scope, restService) {
  var self = this;

  this.test = 'Angular.js works';

  this.restTest = restService.test.load().$promise.then(function(data) {
    self.restEnd(data);
  });

  this.restEnd = function(data) {
    this.weatherData = data;
  };

  this.signupEnd = function(data) {
    this.signupEnd = data;
  };

  this.signup = function(form) {
    console.log(form);
    restService.signup.load().$promise.then(function(data) {
      self.signupEnd(data);
    });
  }

}

angular.module('app')
  .controller('homeCtrl', homeCtrl);
