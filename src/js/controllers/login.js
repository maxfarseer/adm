'use strict';
/**
 * @ngInject
 */
function loginCtrl($scope, $state, restService) {
  var self = this;

  this.login = function(form) {
    restService.login.load({email:form.email, pass: form.pass}).$promise.then(function(data) {
      //TODO: redirect to place after SIGNUP
      if (data.status === 200) {
        $state.go('home');
      }
    });
  };
}

angular.module('app')
  .controller('loginCtrl', loginCtrl);
