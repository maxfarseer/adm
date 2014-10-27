'use strict';
/**
 * @ngInject
 */
function signupCtrl($scope, $state, restService) {
  var self = this;

  this.signup = function(form) {
    restService.signup.load({email:form.email, pass: form.pass}).$promise.then(function(data) {
      $state.go('home');
    });
  };
}

angular.module('app')
  .controller('signupCtrl', signupCtrl);
