'use strict';
/**
 * @ngInject
 */
function homeCtrl($scope, $resource, restService) {
  var self = this;

  this.test = 'Angular.js works';

  restService.getUsers.load().$promise.then(function(data) {
    console.log(data);
    self.users = data.data;
  });

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
