.PHONY: test test_text help
.DEFAULT_GOAL= help

test: vendor ## Run all tests
	php -dxdebug.mode=coverage ./vendor/bin/phpunit tests

test_text: vendor ## Run all tests with code coverage report in text format
	php -dxdebug.mode=coverage ./vendor/bin/phpunit tests --coverage-text

help:
	@grep -E '(^[a-zA-Z_-]+:.*?##.*$$)|(^##)' $(MAKEFILE_LIST) | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[32m%-10s\033[0m %s\n", $$1, $$2}' | sed -e 's/\[32m##/[33m/'