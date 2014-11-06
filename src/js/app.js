'use strict';

var outputDir = '../../builds/development';

/**
 * @ngInject
 */
function config($stateProvider, $urlRouterProvider) { //, $locationProvider, $httpProvider) {

  var access = routingConfig.accessLevels;
  //$httpProvider.defaults.withCredentials = true;

  $urlRouterProvider.otherwise('/404');

  $stateProvider
  //public
  .state('public', {
    abstract: true,
    template: '<ui-view/>',
    data: {
      access: access.public
    }
  })
  .state('public.404', {
    url: '/404',
    templateUrl: outputDir + '/js/views/404.html',
  })
  .state('public.main', {
    url: '/main',
    templateUrl: outputDir + '/js/views/main.html',
    controller: 'mainCtrl',
    controllerAs: 'main'
  })
  //anon
  .state('anon', {
    abstract: true,
    template: '<ui-view/>',
    data: {
      access: access.anon,
      spiker: '200'
    },

  })
  .state('anon.login', {
    url: '/login',
    templateUrl: outputDir + '/js/views/login.html',
    controller: 'loginCtrl',
    controllerAs: 'login'
  })
  .state('anon.signup', {
    url: '/signup',
    templateUrl: outputDir + '/js/views/signup.html',
    controller: 'signupCtrl',
    controllerAs: 'signup'
  })
  //user
  .state('user', {
    abstract: true,
    template: '<ui-view/>',
    data: {
        access: access.user
    }
  })
  .state('user.home', {
    url: '/home',
    templateUrl: outputDir + '/js/views/home.html',
    controller: 'homeCtrl',
    controllerAs: 'home'
  })
  .state('user.data', {
    url: '/data',
    templateUrl: outputDir + '/js/views/data.html',
    controller: 'dataCtrl',
    controllerAs: 'data',
    resolve: {
      resolveTest: function(restService) {
        return restService.git.load().$promise;
      }
    }
  });

}

/**
 * @ngInject
 */
function run($rootScope, $state, $stateParams, restService, Auth) {
  $rootScope.restService = restService;
  $rootScope.root = $rootScope;

  $rootScope.$on('$stateChangeStart', function (event, toState, toStateParams, fromState) { //fromParams

    if(!('data' in toState) || !('access' in toState.data)){
      $rootScope.error = "Access undefined for this state";
      event.preventDefault();
    }

    /*else if (!Auth.authorize(toState.data.access)) {
      $rootScope.error = "Seems like you tried accessing a route you don't have access to...";
      event.preventDefault();

      if(fromState.url === '^') {
        if(Auth.isLoggedIn()) {
          $state.go('user.home');
        } else {
          $rootScope.error = null;
          $state.go('anon.login');
        }
      }
    }*/

  });
}

angular.module('app', ['ui.router', 'ngResource', 'ngCookies'])
  .config(config)
  .run(run)
  ;
