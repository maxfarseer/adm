'use strict';
/**
 * @ngInject
 */
function homeCtrl($scope, $state, $cookieStore, restService, notify) {
  var self = this;

  this.getUserInfo = function() {
    restService.getUserInfo.load().$promise.then(function(data) {
      if (data.status === 200) {
        var profile = data.data;
        self.user = profile;
        self.realpresent = profile.real_present ? true : false;
        self.virtualpresent = profile.virtual_present ? true : false;
      } else {
        notify({message: data.data, classes: 'alert-danger'});
      }
    });
    /*var profile = {
      real_present: {
        f_name: 'Max',
        s_name: 'Pats',
        address: 'Msk',
        status: 'verifying' //blocked
      },
      virtual_present: {
        email: 'maxma@ma.ru',
        f_name: 'Max'
      }
    };

    self.realpresent = profile.real_present ? true : false;
    self.virtualpresent = profile.virtual_present ? true : false;

    self.user = profile;*/
  };

  this.getUserInfo();

  this.userUpdate = function(user) {
    restService.userUpdate.load({user: JSON.stringify(user)}).$promise.then(function(data) {
      notify.closeAll();
      if (data.status === 200) {
        notify({message: data.data, classes: 'alert-success'});
      } else {
        notify({message: data.data, classes: 'alert-danger'});
      }
    });
  };

  this.getRecipient = function() {
    $scope.recipient = {
      address: 'Москва, Кировоградская 17к1 кв 145',
      f_name: 'Максим',
      s_name: 'Пацианский'
    };
  };



}

angular.module('app')
  .controller('homeCtrl', homeCtrl);
