'use strict';
/**
 * @ngInject
 */
function topMenuCtrl($scope, $state, $cookieStore, restService) {
  var self = this;
  $scope.$watch('useradm15', function(newVal) {
    self.isLoggedIn = newVal;
  });

  this.logout = function() {
    restService.logout.load().$promise.then(function(data) {
      if (data.status === 200) {
        $cookieStore.remove('_ADM15');
        $state.go('public.main');
      }
    });
  };
}

angular.module('app')
  .controller('topMenuCtrl', topMenuCtrl);
