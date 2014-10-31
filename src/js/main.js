'use strict';

var outputDir = '../../builds/development';

/**
 * @ngInject
 */
function config($stateProvider, $urlRouterProvider) {
  $urlRouterProvider.otherwise('/');

  $stateProvider
  .state('main', {
    url: '/',
    templateUrl: outputDir + '/js/views/main.html',
    controller: 'mainCtrl',
    controllerAs: 'main'
  })
  .state('main.reg', {
    url: 'main/reg',
    template: '<div>registration area</div>',
    views: {
      'filters': {
        template: '<h4>Filter inbox</h4>',
        controller: function($scope) {}
      },
      'huilters': {
        template: '<h4>Huilter inbox</h4>',
        controller: function($scope) {}
      }
    }
  })
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
function run($rootScope, $state, $stateParams, restService, authorization, principal) {
  $rootScope.restService = restService;
  $rootScope.root = $rootScope;

  $rootScope.$on('$stateChangeStart', function(event, toState, toStateParams) {
    // track the state the user wants to go to; authorization service needs this
    $rootScope.toState = toState;
    $rootScope.toStateParams = toStateParams;
    // if the principal is resolved, do an authorization check immediately. otherwise,
    // it'll be done when the state it resolved.
    if (principal.isIdentityResolved()) {
      authorization.authorize();
    }
  });
}

angular.module('app', ['ui.router', 'ngResource'])
  .config(config)
  .run(run)
  ;
