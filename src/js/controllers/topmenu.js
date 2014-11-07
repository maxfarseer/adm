'use strict';
/**
 * @ngInject
 */
function topMenuCtrl($scope) {
  var self = this;
  $scope.$watch('useradm15', function(newVal) {
    self.isLoggedIn = newVal;
  });
}

angular.module('app')
  .controller('topMenuCtrl', topMenuCtrl);
