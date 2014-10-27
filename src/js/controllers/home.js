'use strict';
/**
 * @ngInject
 */
function homeCtrl($scope, restService) {
  var self = this;

  this.test = 'Angular.js works';

  function getUsers() {
    restService.getUsers.load().$promise.then(function(data) {
      self.users = data.data;
    });
  }

  getUsers();

  this.logout = function() {
    restService.logout.load().$promise.then(function(data) {
      //TODO: redirect to place after SIGNUP
      alert(data.data);
    });
  };

}

angular.module('app')
  .controller('homeCtrl', homeCtrl);
