'use strict';
/**
 * @ngInject
 */
function dataCtrl($scope, resolveTest, restService) {
  var self = this;

  this.githubResponce = resolveTest;

}

angular.module('app')
  .controller('dataCtrl', dataCtrl);
