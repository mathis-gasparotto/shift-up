COMPOSE=docker compose
PHP_CONTAINER=php
EXEC=$(COMPOSE) exec $(PHP_CONTAINER)
CONSOLE=bin/console

.DEFAULT_GOAL := help
help: ## Outputs this help screen
		@grep -E '(^[a-zA-Z0-9_-]+:.*?##.*$$)|(^##)' $(MAKEFILE_LIST) | awk 'BEGIN {FS = ":.*?## "}{printf "\033[32m%-30s\033[0m %s\n", $$1, $$2}' | sed -e 's/\[32m##/[33m/'
serve: ## Start the server locally
	$(CONSOLE) serve
## —— Docker :baleine: ——————————————————————————————————————————————————————————————
start: ## Start the containers
	$(COMPOSE) up -d --remove-orphans
stop: ## Build and start the containers
	$(COMPOSE) down
restart: stop start ## Restart the containers

cc-all: cc db-cc ## Clear all cache

## —— Init :notes: ——————————————————————————————————————————————————————————————
composer-install: ## Install the PHP dependencies
	$(EXEC) composer install
jwt-key-generate: ## Generate the JWT key
	$(EXEC) $(CONSOLE) jwt
init-project-pictures: ## Init project pictures
	$(EXEC) $(CONSOLE) init-project-pictures
db-init: db-update ## Create database
api-init: composer-install db-init jwt-key-generate init-project-pictures ## All init for api

php-sh: ## Connect to the PHP FPM container
	@echo -----------------------Enter contener PHP-------------------------
	$(COMPOSE) exec php sh
db-diff: ## Doctrine migrations diff
	$(EXEC) $(CONSOLE) doctrine:migration:diff
db-migrate: ## Doctrine migrations migrate
	$(EXEC) $(CONSOLE) doctrine:migration:migrate
migration: ## Connect to the PHP FPM container
	@echo -----------------------Enter contener PHP-------------------------
	$(EXEC) $(CONSOLE) make:migration
	$(EXEC) $(CONSOLE) doctrine:migration:migrate

## —— Database :boîte_rangement_fiches: ————————————————————————————————————————————————————————————
db-update: ## Update database schema
	$(EXEC) $(CONSOLE) doctrine:schema:update --force
#refresh-token:
#   $(EXEC) $(CONSOLE) doctrine:query:sql "CREATE TABLE refresh_tokens (id int(11) NOT NULL AUTO_INCREMENT, refresh_token varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,username varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,valid datetime NOT NULL)"
db-reset: ## Recreate database dev
	@echo ----------------- RESET DEV DB ------------------
	$(EXEC) $(CONSOLE) --env=dev doctrine:database:drop --force --if-exists
	$(EXEC) $(CONSOLE) --env=dev doctrine:database:create --if-not-exists
	$(EXEC) $(CONSOLE) --env=dev doctrine:schema:create -n
	$(EXEC) $(CONSOLE) --env=dev hautelook:fixtures:load -n --purge-with-truncate
db-test: ## Recreate database test
	@echo ----------------- RESET TEST DB ------------------
	$(EXEC) $(CONSOLE) --env=test doctrine:database:drop --force --if-exists
	$(EXEC) $(CONSOLE) --env=test doctrine:database:create --if-not-exists
	$(EXEC) $(CONSOLE) --env=test doctrine:schema:create -n
load-fixtures: ## Load database fixtures
	$(EXEC) $(CONSOLE) hautelook:fixtures:load -n --purge-with-truncate
db-cc: ## Clear database cache
	@echo -----------------------Emptying database cache-------------------------
	$(EXEC) $(CONSOLE) doctrine:cache:clear-metadata
	$(EXEC) $(CONSOLE) doctrine:cache:clear-query
	$(EXEC) $(CONSOLE) doctrine:cache:clear-result
## —— Tools :marteau_et_clé_anglaise:️ ———————————————————————————————————————————————————————————————
encode-password: ## Encode password
	$(EXEC) $(CONSOLE) security:encode-password
## —— Symfony :note_de_musique: ————————————————————————————————————————————————————————————
cc: ## Cache clear
	@echo -----------------------Emptying symfony cache-------------------------
	$(EXEC) $(CONSOLE) cache:clear
router: ## Debug router
	@echo -----------------------Emptying symfony cache-------------------------
	$(EXEC) $(CONSOLE) debug:router
#refresh-token-test: ## Add table Refresh Token env test
#   $(EXEC) $(CONSOLE) --env=test doctrine:query:sql "CREATE TABLE refresh_tokens (id int(11) PRIMARY KEY NOT NULL AUTO_INCREMENT, refresh_token varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,username varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,valid datetime NOT NULL)"
## —— Tests :bloc_notes: ———————————————————————————————————————————————————————————————
clean-tests: ## Clean output folder
	@echo -------------------- clean tests --------------------
	$(EXEC) vendor/bin/codecept clean
	$(EXEC) vendor/bin/codecept build
run-test-functional: ## Run functional tests
	@echo ----------------- launch api tests ------------------
	$(EXEC) vendor/bin/codecept run functional --group=auth --quiet
run-test-unit: ## Run unit tests
	@echo ----------------- launch unit tests ------------------
	$(EXEC) vendor/bin/codecept run unit
## —— PHPCS :coupe_de_cheveux: ———————————————————————————————————————————————————————————————
run-phpcs: ## Run PHP CodeSniffer
	@echo ----------------- launch phpcs ------------------
	$(EXEC) vendor/bin/phpcs src/ tests/
run-phpcs-files: ## Run PHP CodeSniffer by files
	# Example : make run-phpcs-files FILES="path/to/class/ClassController.php path/to/class/ClassTwoController.php"
	$(EXEC) vendor/bin/phpcs --standard=PSR12 --exclude=Generic.Files.LineLength $(FILES)
## —— Key JWT :marteau_et_clé_anglaise: ———————————————————————————————————————————————————————————————
generate-key-jwt: ## Load key JWT
	$(EXEC) $(CONSOLE) lexik:jwt:generate-keypair
## —— Messenger :marteau_et_clé_anglaise: ———————————————————————————————————————————————————————————————
consume-messenger-notification_message: ## Run PHP Worker messenger
	@echo ----------------- launch phpcs ------------------
	$(EXEC) $(CONSOLE) messenger:consume notification_message
#send-notification-test: ## Run PHP Send notification test
#	@echo ----------------- launch phpcs ------------------
#	$(EXEC) $(CONSOLE) send:notification:test
## —— Migration :marteau_et_clé_anglaise: ———————————————————————————————————————————————————————————————
run-migration:
	$(EXEC) $(CONSOLE) make:migration
test: clean-tests db-test run-test-functional run-test-unit run-phpcs
## —— Stripe ———————————————————————————————————————————————————————————————
stripe-cli-install: ## Install stripe-cli
	curl -s https://packages.stripe.dev/api/security/keypair/stripe-cli-gpg/public | gpg --dearmor | sudo tee /usr/share/keyrings/stripe.gpg
	echo "deb [signed-by=/usr/share/keyrings/stripe.gpg] https://packages.stripe.dev/stripe-cli-debian-local stable main" | sudo tee -a /etc/apt/sources.list.d/stripe.list
	sudo apt update
	sudo apt install stripe
	stripe login
run-stripe: ## Run Stripe Webhook
	stripe listen --forward-to http://127.0.0.1:8080/webhook/confirmation_stripe_payment
stripe-events: ## Add Stripe events
	stripe trigger checkout.session.completed