.DEFAULT_GOAL := help
.PHONY: help test lint

help: ## Show this message
	@grep -E '^[a-zA-Z_-]+:.*?## .*$$' $(MAKEFILE_LIST) | sort \
	| awk 'BEGIN {FS = ":.*?## "}; {printf "\033[36m%-30s\033[0m %s\n", $$1, $$2}'

test: ## Run application tests
	@$(PHP_CMD) ./vendor/bin/phpunit tests -v --bootstrap tests/bootstrap.php

lint: ## Run cs-fixer linter
	@echo "Code style fixing..."
	@docker run --rm -v $(PWD):/data cytopia/php-cs-fixer fix . --diff