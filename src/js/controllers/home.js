'use strict';
/**
 * @ngInject
 */
function homeCtrl($scope, $resource, restService) {
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
    // console.log($resource);
    console.log(form);

    /*var CreditCard = $resource('http://localhost:8888/api/signup/',
      {userId:123, cardId:'@id'}, {
      charge: {method:'POST', params:{charge:true}}
    });*/



    restService.getusers.load().$promise.then(function(data) {
      self.signupEnd(data);
    });

    /*restService.signup({amount:9.99}).load().$promise.then(function(data) {
      self.signupEnd(data);
    });*/
  };

}

angular.module('app')
  .controller('homeCtrl', homeCtrl);
