/* jslint node: true */
/* global describe, it, expect */

'use strict';

var math = require('../src/js/modules/math');

describe("#math lib", function () {
  it("returns triple X value", function () {
    var triple = math.tripled(9);
    expect(triple).toBe(27);
  });

  it("returns the correct squared value", function () {
    var squared = math.squared(9);
    expect(squared).toBe(81);
  });
});
