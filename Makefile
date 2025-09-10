#
# Actions ------------------------------------------
#

up: ## Start all containers
	docker compose up -d

down: ## Stop all containers
	docker compose down
