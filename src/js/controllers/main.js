'use strict';
/**
 * @ngInject
 */
function mainCtrl($scope, restService) {
  var self = this;

  this.test = 'Main';
}

angular.module('app')
  .controller('mainCtrl', mainCtrl);
