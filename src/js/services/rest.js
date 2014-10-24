'use strict';
/**
 * @ngInject
 */
function restService($resource) {
  var rootLink = 'http://localhost:8001/builds/development';
  var rest = {
      test: $resource('http://api.openweathermap.org/data/2.5/weather?q=London,uk', {}, {
        load: {method: 'GET'}
      }),
      git: $resource('https://api.github.com/users', {}, {
        load: {method: 'GET', isArray: true}
      }),
      signup: $resource('/api/signup', {}, {
        load: {method: 'GET'}
      }),
    };

  return rest;
}

angular.module('app')
  .factory('restService',restService);
