'use strict';
/**
 * @ngInject
 */
function dataCtrl($scope, resolveTest) {

  this.githubResponce = resolveTest;

}

angular.module('app')
  .controller('dataCtrl', dataCtrl);
