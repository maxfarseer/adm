'use strict';

function myCustomer() {
  return {
    restrict: 'AE',
    template: 'MY customer DIRECTIVE compressed'
  };
}

angular.module('app')
  .directive('myCustomer',myCustomer);
