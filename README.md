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

Copyright: me & Spiker

LINKS
===============
http://localhost:8888/builds/development/#/signup
http://localhost:8888/builds/development/#/login

status users
0 - registered
1 - can donate digit present (email)
2 - full registered (all info in profile, blocked address) ->can donate present
3 - approved user  ->can get present

type presents
pkg     -   pakage present
digit   -   present from site

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
        status: 403 - no access
                0 - error
                200 - OK

        real_present {
           f_name
           s_name
           address
           status: verifying/blocked
        }
        virtual_present {
           email
           f_name
        }
        real_client {
           f_name
           s_name
           address
        }
        virtual_client {
           f_name
        }

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

USERDIGIT

    request
        GET api/userdigit

    answer
        JSON {"data":...,"status":...}

        status: 403 - no access
                0 - error
                200 - OK
