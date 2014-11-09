'use strict';
/**
 * @ngInject
 */
function homeCtrl($scope, $state, $cookieStore, restService, notify) {
  var self = this;

  this.getUserInfo = function() {
    restService.getUserInfo.load().$promise.then(function(data) {
      self.user = data.data;
    });
  };

  this.getUserInfo();

  this.userUpdate = function(user) {
    restService.userUpdate.load(user).$promise.then(function(data) {
      if (data.status === 200) {
        notify({message: data.data, classes: 'alert-success'});
      } else {
        notify({message: data.data, classes: 'alert-danger'});
      }
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

  this.getRecipient = function() {
    console.log('qqq');
    console.log($scope);
    $scope.recipient = {
      address: 'Москва, Кировоградская 17к1 кв 145',
      f_name: 'Максим',
      s_name: 'Пацианский'
    };
  };



}

angular.module('app')
  .controller('homeCtrl', homeCtrl);
