.PHONY: help build build-php-prod build-php-test lint lint-php test test-php clean clean-full copy-config post-lint

SHELL=/bin/bash -o pipefail

.DEFAULT_GOAL := help
COMPOSER_BIN := $(shell composer config bin-dir 2> /dev/null)

help: ## Display general help about this command
	@echo 'Makefile targets:'
	@grep -E '^[a-zA-Z0-9_-]+:.*?## .*$$' Makefile \
	| sed -n 's/^\(.*\): \(.*\)##\(.*\)/    \1 :: \3/p' \
	| column -t -c 1  -s '::'

build: build-php-prod ## Build the application

build-php-prod:
	composer install --no-dev --optimize-autoloader --classmap-authoritative --no-progress --no-interaction

build-php-test:
	composer install --no-progress --no-interaction

lint: lint-php ## Lint the application

lint-php: build-php-test
	$(COMPOSER_BIN)/php-cs-fixer fix
	$(COMPOSER_BIN)/phpcs
	$(COMPOSER_BIN)/phpstan analyse src --level=max

test: test-php ## Test the application

test-php: build-php-test
	$(COMPOSER_BIN)/phpunit tests

clean: ## Remove files listed in .gitignore (possibly with some exceptions)
	@git init 2> /dev/null
	git clean -Xdff

clean-full:
	@git init 2> /dev/null
	git clean -Xdff

copy-config: ## Copy missing config files into place

post-lint:
	@git diff --exit-code --quiet || (echo 'There should not be any changes after the lint runs' && git status && exit 1;)
