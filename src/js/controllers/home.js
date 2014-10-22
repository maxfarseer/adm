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

}

angular.module('app')
  .controller('homeCtrl', homeCtrl);
