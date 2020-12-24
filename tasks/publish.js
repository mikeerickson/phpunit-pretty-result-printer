#!/usr/bin/env node

/*-------------------------------------------------------------------------------------------
 * Copyright (c) Mike Erickson / Codedungeon.  All rights reserved.
 * Licensed under the MIT license.  See LICENSE in the project root for license information.
 * -----------------------------------------------------------------------------------------*/

const fs = require("fs")
const path = require("path")
const execSync = require("child_process").execSync
const Messenger = require("@codedungeon/messenger")

let version = ""
let name = ""
let _TESTING = false

let packageFilename = path.join(__dirname, "..", "package.json")
if (fs.existsSync(packageFilename)) {
    let pkgInfo = require(packageFilename)
    if (pkgInfo.hasOwnProperty("version")) {
        version = pkgInfo.version
    }
    if (pkgInfo.hasOwnProperty("name")) {
        name = pkgInfo.name
    }
} else {
    let composerFilename = path.join(__dirname, "..", "composer.json")
    let composerInfo = require(composerFilename)
    if (composerInfo.hasOwnProperty("version")) {
        version = composerInfo.version
    }
}

console.log("")
Messenger.info(`Publishing ${version} ...`, "INFO")

console.log("")

Messenger.success(`✓ Creating Github tag ${version}`)

console.log("")

if (!_TESTING) {
    let result = execSync(`git tag "${version}" && git push --tags --quiet`)
    console.log(result.toString())
    console.log("")
} else {
    Messenger.note("*** TESTING MODE Publishing Command Skipped ***")
}

console.log("")
Messenger.success(`${name} ${version} Published Successfully`, "SUCCESS")
