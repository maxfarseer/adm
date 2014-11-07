'use strict';
/**
 * @ngInject
 */
function loginCtrl($scope, $state, $cookieStore, restService) {
  $scope.form = {};

  $scope.form.email = 'nikozor@ya.ru';
  $scope.form.pass = '123';

  this.login = function(form) {
    restService.login.load({email:form.email, pass: form.pass}).$promise.then(function(data) {
      console.log(data.status);
      if (data.status === 200) {
        //TODO: data.role from API
        $cookieStore.put('_ADM15','user');
        $state.go('user.home');
      }
    });
  };
}

angular.module('app')
  .controller('loginCtrl', loginCtrl);
