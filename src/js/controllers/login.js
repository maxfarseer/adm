'use strict';
/**
 * @ngInject
 */
function loginCtrl($scope, $state, $cookieStore, restService) {
  var self = this;

  $scope.form = {};

  $scope.form.email = 'maxf@mail.ru';
  $scope.form.pass = '123';

  this.login = function(form) {
    restService.login.load({email:form.email, pass: form.pass}).$promise.then(function(data) {
      console.log(data.status);
      if (data.status === 200) {
        $state.go('user.home');
      }
    });
  };
}

angular.module('app')
  .controller('loginCtrl', loginCtrl);
