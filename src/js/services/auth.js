'use strict';
/**
 * @ngInject
 */
function Auth($rootScope, $state) {
  return {
    authorize: function() {
      return 'authorized';
    }
  };
}

angular.module('app')
  .factory('Auth',Auth);
