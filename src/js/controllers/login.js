'use strict';
/**
 * @ngInject
 */
function loginCtrl($scope, $state, $cookieStore, restService, notify) {
  $scope.form = {};

  $scope.form.email = 'nikozor@ya.ru';
  $scope.form.pass = '123';

  this.login = function(form) {
    notify({message: 'Проверяю данные...'});
    restService.login.load({email:form.email, pass: form.pass}).$promise.then(function(data) {
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
