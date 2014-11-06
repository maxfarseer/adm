'use strict';
/**
 * @ngInject
 */
function Auth($cookieStore, restService) {

  var currentUser = $cookieStore.get('_identity');
  console.log(currentUser);
  return {
    isLoggedIn: function() {
      if (currentUser) {
        console.log('isLoggedIn');
        return true;
      }
      //return restService.getUserInfo.load().$promise;
    }
  };
}

angular.module('app')
  .factory('Auth',Auth);
