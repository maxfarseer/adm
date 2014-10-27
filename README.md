ADM 2015
================

Install:
- Node.js (http://nodejs.org/download/)
- Gulp global (npm install -g gulp)
- Bower global (npm install -g bower)
- npm install
- bower install

Start:
- gulp build

Copyright: me & SpikerIII

LINKS
===============
http://localhost:8888/builds/development/#/signup
http://localhost:8888/builds/development/#/login


API

LOGIN
    request
        POST api/login

    parameters
        email
        pass

    answer
        JSON {"status": "...", "data": ...}
        status: 0 - error
                200 - OK

LOGOUT
    request
        GET api/logout

    answer
        JSON {"status": "...", "data": ...}
        status: 0 - error
                200 - OK

SIGNUP
    request
        POST api/signup

    parameters
        email
        pass

    answer
        JSON {"status": "...", "data": ...}
        status: 0 - error
                200 - OK

GETUSERS
    request
        GET api/getusers

    answer
        JSON {"status": "...", "data": ...}
        status: 0 - error
                200 - OK
