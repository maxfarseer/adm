'use strict';
/**
 * @ngInject
 */
function homeCtrl($scope, restService) {
  var self = this;

  this.getUserInfo = function() {
    restService.getUserInfo.load().$promise.then(function(data) {
      self.user = data.data;
    });
  };

  this.getUserInfo();

  this.logout = function() {
    restService.logout.load().$promise.then(function(data) {
      //TODO: redirect to place after SIGNUP
      alert(data.data);
    });
  };



}

angular.module('app')
  .controller('homeCtrl', homeCtrl);
