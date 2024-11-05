COMPOSE=docker compose
EXEC_PHP=$(COMPOSE) exec php
EXEC_FRONT=$(COMPOSE) exec app
CONSOLE=php bin/console

.DEFAULT_GOAL := help

start:
	$(COMPOSE) up -d --remove-orphans

stop:
	$(COMPOSE) down

restart: stop start

restart-front:
	$(COMPOSE) restart app

restart-api:
	$(COMPOSE) restart php

api-db-reset:
	cd api && $(MAKE) db-reset

sh-front:
	$(EXEC_FRONT) sh

sh-api:
	$(EXEC_PHP) sh

log-app:
	$(COMPOSE) logs app -f

log-api:
	$(COMPOSE) logs php -f

bin/console:
	$(EXEC_PHP) $(CONSOLE) $(filter-out $@,$(MAKECMDGOALS))

yarn-add: ## Add package yarn
	$(EXEC_FRONT) yarn add $(filter-out $@,$(MAKECMDGOALS))
	(cd ./quasar/ && yarn)
	make restart-front

yarn-remove: ## Add package yarn
	$(EXEC_FRONT) yarn remove $(filter-out $@,$(MAKECMDGOALS))
	(cd ./quasar/ && yarn)
	make restart-front

bin/console: ## Run api symfony command
	$(EXEC_PHP) $(CONSOLE) $(filter-out $@,$(MAKECMDGOALS))

## —— Init :notes: ——————————————————————————————————————————————————————————————
composer-install: ## Install the PHP dependencies
	$(EXEC_PHP) composer install
jwt-key-generate: ## Generate the JWT key
	$(EXEC_PHP) $(CONSOLE) jwt
init-project-pictures: ## Init project pictures
	$(EXEC_PHP) $(CONSOLE) init-project-pictures
init-subscriptions: ## Init project pictures
	$(EXEC_PHP) $(CONSOLE) init-subscriptions
db-create: ## Create database
	$(EXEC_PHP) $(CONSOLE) doctrine:database:create
db-init: db-create db-migrate ## init database
api-init: composer-install db-init jwt-key-generate init-project-pictures init-subscriptions ## All init for api

help: ## Outputs this help screen
		@grep -E '(^[a-zA-Z0-9_-]+:.*?##.*$$)|(^##)' $(MAKEFILE_LIST) | awk 'BEGIN {FS = ":.*?## "}{printf "\033[32m%-30s\033[0m %s\n", $$1, $$2}' | sed -e 's/\[32m##/[33m/'
## —— Makefile for inealab-api ——————————————————————————————————————————————————————————————
## —— Docker :baleine: ——————————————————————————————————————————————————————————————
	

## —— Database :boîte_rangement_fiches: ————————————————————————————————————————————————————————————
db-diff: ## Doctrine migrations diff
	$(EXEC_PHP) $(CONSOLE) doctrine:migration:diff
db-update: ## Update database schema
	$(EXEC_PHP) $(CONSOLE) doctrine:schema:update --force
#refresh-token:
#   $(EXEC_PHP) $(CONSOLE) doctrine:query:sql "CREATE TABLE refresh_tokens (id int(11) NOT NULL AUTO_INCREMENT, refresh_token varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,username varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,valid datetime NOT NULL)"
db-reset: ## Recreate database dev
	@echo ----------------- RESET DEV DB ------------------
	$(EXEC_PHP) $(CONSOLE) --env=dev doctrine:database:drop --force --if-exists
	$(EXEC_PHP) $(CONSOLE) --env=dev doctrine:database:create --if-not-exists
	$(EXEC_PHP) $(CONSOLE) --env=dev doctrine:schema:create -n
	$(EXEC_PHP) $(CONSOLE) --env=dev hautelook:fixtures:load -n --purge-with-truncate
db-test: ## Recreate database test
	@echo ----------------- RESET TEST DB ------------------
	$(EXEC_PHP) $(CONSOLE) --env=test doctrine:database:drop --force --if-exists
	$(EXEC_PHP) $(CONSOLE) --env=test doctrine:database:create --if-not-exists
	$(EXEC_PHP) $(CONSOLE) --env=test doctrine:schema:create -n
load-fixtures: ## Load database fixtures
	$(EXEC_PHP) $(CONSOLE) hautelook:fixtures:load -n --purge-with-truncate
## —— Tools :marteau_et_clé_anglaise:️ ———————————————————————————————————————————————————————————————
encode-password: ## Encode password
	$(EXEC_PHP) $(CONSOLE) security:encode-password
## —— Symfony :note_de_musique: ————————————————————————————————————————————————————————————
cc: ## Cache clear
	@echo -----------------------Emptying symfony cache-------------------------
	$(EXEC_PHP) $(CONSOLE) cache:clear
cc-test: ## Test Cache clear
	@echo -----------------------Emptying symfony test cache-------------------------
	$(EXEC_PHP) $(CONSOLE) cache:clear --env=test
router: ## Debug router
	@echo -----------------------Emptying symfony cache-------------------------
	$(EXEC_PHP) $(CONSOLE) debug:router
#refresh-token-test: ## Add table Refresh Token env test
#   $(EXEC_PHP) $(CONSOLE) --env=test doctrine:query:sql "CREATE TABLE refresh_tokens (id int(11) PRIMARY KEY NOT NULL AUTO_INCREMENT, refresh_token varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,username varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,valid datetime NOT NULL)"
## —— Tests :bloc_notes: ———————————————————————————————————————————————————————————————
clean-tests: ## Clean output folder
	@echo -------------------- clean tests --------------------
	$(EXEC_PHP) vendor/bin/codecept clean
	$(EXEC_PHP) vendor/bin/codecept build
run-test-functional: ## Run functional tests
	@echo ----------------- launch api tests ------------------
	$(EXEC_PHP) vendor/bin/codecept run functional --group=auth --quiet
run-test-unit: ## Run unit tests
	@echo ----------------- launch unit tests ------------------
	$(EXEC_PHP) vendor/bin/codecept run unit
## —— PHPCS :coupe_de_cheveux: ———————————————————————————————————————————————————————————————
run-phpcs: ## Run PHP CodeSniffer
	@echo ----------------- launch phpcs ------------------
	$(EXEC_PHP) vendor/bin/phpcs src/ tests/
run-phpcs-files: ## Run PHP CodeSniffer by files
	# Example : make run-phpcs-files FILES="path/to/class/ClassController.php path/to/class/ClassTwoController.php"
	$(EXEC_PHP) vendor/bin/phpcs --standard=PSR12 --exclude=Generic.Files.LineLength $(FILES)
## —— Key JWT :marteau_et_clé_anglaise: ———————————————————————————————————————————————————————————————
generate-key-jwt: ## Load key JWT
	$(EXEC_PHP) $(CONSOLE) lexik:jwt:generate-keypair
## —— Messenger :marteau_et_clé_anglaise: ———————————————————————————————————————————————————————————————
consume-messenger-notification_message: ## Run PHP Worker messenger
	@echo ----------------- launch phpcs ------------------
	$(EXEC_PHP) $(CONSOLE) messenger:consume notification_message
#send-notification-test: ## Run PHP Send notification test
#	@echo ----------------- launch phpcs ------------------
#	$(EXEC_PHP) $(CONSOLE) send:notification:test
## —— Migration :marteau_et_clé_anglaise: ———————————————————————————————————————————————————————————————
db-migrate: ## Doctrine migrations migrate
	$(EXEC_PHP) $(CONSOLE) doctrine:migration:migrate
make-migration: ## Doctrine generate migration
	$(EXEC_PHP) $(CONSOLE) make:migration
migration: ## Connect to the PHP FPM container
	@echo -----------------------Enter contener PHP-------------------------
	$(EXEC_PHP) $(CONSOLE) make:migration
	$(EXEC_PHP) $(CONSOLE) doctrine:migration:migrate
test:
	clean-tests db-test run-test-functional run-test-unit run-phpcs
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
## —— Chmod ———————————————————————————————————————————————————————————————
chmod-public-media: ## Chmod public media
	sudo chmod -R 777 api/public/media
