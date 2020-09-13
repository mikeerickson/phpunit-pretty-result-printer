#!/usr/bin/env node

/* global require */

const fs = require('fs');
const path = require('path');

let version = '';

let packageFilename = path.join(__dirname, '..', 'package.json');
if (fs.existsSync(packageFilename)) {
  let pkgInfo = require(packageFilename);
  if (pkgInfo.hasOwnProperty('version')) {
    version = pkgInfo.version;
  }
} else {
  let composerFilename = path.join(__dirname, '..', 'composer.json');
  let composerInfo = require(composerFilename);
  if (composerInfo.hasOwnProperty('version')) {
    version = composerInfo.version;
  }
}

console.log(version);

