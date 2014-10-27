'use strict';
/**
 * @ngInject
 */
function restService($resource) {
  var rootLink = 'http://localhost:8888/';
  var rest = {
      git: $resource('https://api.github.com/users', {}, {
        load: {method: 'GET', isArray: true}
      }),
      getUsers: $resource(rootLink + 'api/getusers', {}, {
        load: {method: 'GET'}
      }),
      signup: $resource(rootLink + 'api/signup/', {}, {
        //load: {method: 'POST', params:{email:'hello@mail.ru', pass: 'test'}}
        load: {method: 'POST'}
      }),
    };

  return rest;
}

angular.module('app')
  .factory('restService',restService);
