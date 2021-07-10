.PHONY: help full full-php build build-php-prod build-php-test lint lint-php test test-php clean clean-full copy-config projectl git-change-check

SHELL=/bin/bash -o pipefail

.DEFAULT_GOAL := help

help: ## Display general help about this command
	@echo 'Makefile targets:'
	@grep -E '^[a-zA-Z0-9_-]+:.*?## .*$$' Makefile \
	| sed -n 's/^\(.*\): \(.*\)##\(.*\)/    \1 :: \3/p' \
	| column -t -c 1  -s '::'

full: lint test build

full-php: lint-php test-php build-php

build: build-php-prod ## Build the application

build-php-prod:
	composer install --no-dev --optimize-autoloader --classmap-authoritative --no-progress --no-interaction

build-php-test:
	composer install --no-progress --no-interaction

lint: lint-php ## Lint the application

lint-php: build-php-test
	$(shell composer config bin-dir)/php-cs-fixer fix
	$(shell composer config bin-dir)/phpcs
	$(shell composer config bin-dir)/phpstan analyse src --level=max

test: test-php ## Test the application

test-php: build-php-test
	$(shell composer config bin-dir)/phpunit src

clean: ## Remove files listed in .gitignore (possibly with some exceptions)
	@git init 2> /dev/null
	git clean -Xdff

clean-full:
	@git init 2> /dev/null
	git clean -Xdff

copy-config: ## Copy missing config files into place

projectl:
	@go install github.com/aaronellington/projectl@latest
	$(shell go env GOPATH)/bin/projectl

git-change-check:
	@git diff --exit-code --quiet || (echo 'There should not be any changes at this point' && git status && exit 1;)
