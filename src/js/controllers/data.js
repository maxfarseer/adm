'use strict';
/**
 * @ngInject
 */
function dataCtrl($scope, restService) {
  var self = this;

  restService.git.load().$promise.then(function(data) {
    self.restEnd(data);
  });

  this.restEnd = function(data) {
    this.githubResponce = data;
  };
}

angular.module('app')
  .controller('dataCtrl', dataCtrl);
