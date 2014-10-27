'use strict';
/**
 * @ngInject
 */
function restService($resource) {

  // http://www.bennadel.com/blog/2615-posting-form-data-with-http-in-angularjs.htm
  function transformRequest( data, getHeaders ) {
    var headers = getHeaders();
    headers[ "Content-Type" ] = "application/x-www-form-urlencoded;charset=utf-8";
    return( serializeData( data ) );
  }

  function serializeData(data) {
    // If this is not an object, defer to native stringification.
    if ( ! angular.isObject( data ) ) {
      return( ( data === null ) ? "" : data.toString() );
    }
    var buffer = [];
    // Serialize each key in the object.
    for ( var name in data ) {
      if ( ! data.hasOwnProperty( name ) ) {
        continue;
      }
      var value = data[ name ];
      buffer.push(
        encodeURIComponent( name ) +
        "=" +
        encodeURIComponent( ( value === null ) ? "" : value )
      );
    }
    // Serialize the buffer and clean it up for transportation.
    var source = buffer.join( "&" ).replace( /%20/g, "+" );

    return(source);
  }

  var rootLink = window.location.origin;

  var rest = {
      git: $resource('https://api.github.com/users', {}, {
        load: {method: 'GET', isArray: true}
      }),
      getUsers: $resource(rootLink + '/api/getusers', {}, {
        load: {method: 'GET'}
      }),
      signup: $resource(rootLink + '/api/signup', {}, {
        load: {method: 'POST', transformRequest: transformRequest}
      }),
      login: $resource(rootLink + '/api/login', {}, {
        load: {method: 'POST', transformRequest: transformRequest}
      }),
      logout: $resource(rootLink + '/api/logout', {}, {
        load: {method: 'GET'}
      })
    };

  return rest;
}

angular.module('app')
  .factory('restService',restService);
