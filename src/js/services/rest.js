'use strict';
/**
 * @ngInject
 */
function restService($resource) {
  var rootLink = 'http://localhost:8888/';
  var rest = {
      test: $resource('http://api.openweathermap.org/data/2.5/weather?q=London,uk', {}, {
        load: {method: 'GET'}
      }),
      git: $resource('https://api.github.com/users', {}, {
        load: {method: 'GET', isArray: true}
      }),
      getusers: $resource(rootLink + 'api/getusers', {}, {
        load: {method: 'GET'}
      }),
      signup: $resource(rootLink + 'api/signup/', {}, {
        //load: {method: 'POST', params:{mail:'hello@mail.ru', pass: 'test'}}
        load: {method: 'POST', params:{test:'test'}}
      }),
    };

  return rest;
}

angular.module('app')
  .factory('restService',restService);
