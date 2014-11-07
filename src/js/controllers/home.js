'use strict';
/**
 * @ngInject
 */
function homeCtrl($scope, $state, $cookieStore, restService) {
  var self = this;

  this.getUserInfo = function() {
    restService.getUserInfo.load().$promise.then(function(data) {
      self.user = data.data;
    });
  };

  this.getUserInfo();

  this.userUpdate = function(user) {
    restService.userUpdate.load(user).$promise.then(function(data) {
      console.log('userUpdate: ' + data.status);
    });
  };

  this.logout = function() {
    restService.logout.load().$promise.then(function(data) {
      if (data.status === 200) {
        $cookieStore.remove('_ADM15');
        $state.go('public.main');
      }
    });
  };



}

angular.module('app')
  .controller('homeCtrl', homeCtrl);
