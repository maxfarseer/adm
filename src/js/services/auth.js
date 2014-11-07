'use strict';
/**
 * @ngInject
 */
function Auth($cookieStore) {

  return {
    isLoggedIn: function() {
      if ($cookieStore.get('_ADM15')) {
        return 1;
      } else {
        return 0;
      }
    }
  };
}

angular.module('app')
  .factory('Auth',Auth);
