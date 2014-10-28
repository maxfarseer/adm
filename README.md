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

TEST
    request
        GET api/test

    answer
        JSON {"data":{"BD":"..","GET":[],"POST":[]},"status":..}

        status: 0 - error
                200 - OK

        BD: YES - connect
            NO  - bad connect

USERINFO
    request
        GET api/userinfo

    answer
        JSON {"data":{"email":"...","f_name":"...","s_name":"...","address":"..."},"status":...}

        status: 403 - no access
                0 - error
                200 - OK

USERUPT
    request
        POST api/userupt

    parameters
        email
        f_name
        s_name
        address

    answer
        JSON {"data":...,"status":...}

        status: 403 - no access
                0 - error
                200 - OK
