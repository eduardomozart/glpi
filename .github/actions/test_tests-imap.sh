#!/bin/bash
set -e -u -x -o pipefail

PHPUNIT_ADDITIONNAL_OPTIONS=""
if [[ "$CODE_COVERAGE" = true ]]; then
  export COVERAGE_DIR="coverage-imap"
  PHPUNIT_ADDITIONNAL_OPTIONS="--coverage-filter src --coverage-clover phpunit/$COVERAGE_DIR/clover.xml"
else
  PHPUNIT_ADDITIONNAL_OPTIONS="--no-coverage";
fi

vendor/bin/phpunit $PHPUNIT_ADDITIONNAL_OPTIONS phpunit/imap

unset COVERAGE_DIR
