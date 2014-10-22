'use strict';

var outputDir = '../../builds/development';

/**
 * @ngInject
 */
function config($stateProvider, $urlRouterProvider) {
  $urlRouterProvider.otherwise('/home');

  $stateProvider
  .state('home', {
    url: '/home',
    templateUrl: outputDir + '/js/views/home.html',
    controller: 'homeCtrl',
    controllerAs: 'home'
  })
  .state('data', {
    url: '/data',
    templateUrl: outputDir + '/js/views/data.html',
    controller: 'dataCtrl',
    controllerAs: 'data'
  });
}

/**
 * @ngInject
 */
function run($rootScope, restService) {
  $rootScope.restService = restService;
  $rootScope.root = $rootScope;
}

angular.module('app', ['ui.router', 'ngResource'])
  .config(config)
  .run(run)
  ;

//additional directives
//app.directive('myCustomerTwo',require('./directives/my-customer-two'));
//app.directive('myJquery',require('./directives/my-jquery'));
