/* jslint node: true */
/* global describe, it, expect */

'use strict';

var talker = require('../src/js/modules/say-hello');

describe("#Greetings", function () {
  it("returns Greetings, Max", function () {
    var greetings = talker.greetings('Max');
    expect(greetings).toBe('Greetings, Max');
  });
});
