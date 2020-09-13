#!/usr/bin/env bash

source ./tasks/messenger.sh

VERSION=$(./tasks/getVersion.js)
printf "\n"
info "Publishing $VERSION ..." " INFO "

printf "\n"

success "✓ Creating Github tag $VERSION"

git tag "$VERSION" && git push --tags --quiet

printf "\n"
success "Publishing $VERSION Completed Successfully " " SUCCESS "
