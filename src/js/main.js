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
  .state('login', {
    url: '/login',
    templateUrl: outputDir + '/js/views/login.html',
    controller: 'loginCtrl',
    controllerAs: 'login'
  })
  .state('signup', {
    url: '/signup',
    templateUrl: outputDir + '/js/views/signup.html',
    controller: 'signupCtrl',
    controllerAs: 'signup'
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
function run($rootScope, $state, restService) {
  $rootScope.restService = restService;
  $rootScope.root = $rootScope;

  /*$rootScope.$on('$stateChangeStart', function(e, toState, toParams, fromState, fromParams) {
    e.preventDefault();

    restService.getUserInfo.load().$promise.then(function(data) {
      if (data.status === 403) {
        console.log(403);
        //$state.go('login');
      } else if (data.status === 200) {
        console.log(200);
        //$state.go(toState.name);
      }
    });

  });*/
}

angular.module('app', ['ui.router', 'ngResource'])
  .config(config)
  .run(run)
  ;
