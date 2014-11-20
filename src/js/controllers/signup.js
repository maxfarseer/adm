'use strict';
/**
 * @ngInject
 */
function signupCtrl($scope, $state, $cookieStore, restService, notify) {

  this.signup = function(form) {
    notify('Ваш профиль создается...');
    restService.signup.load({email:form.email, pass: form.pass}).$promise.then(function(data) {
      if (data.status === 200) {
        $cookieStore.put('_ADM15','user');
        $state.go('user.home');
      }
    });
  };
}

angular.module('app')
  .controller('signupCtrl', signupCtrl);
