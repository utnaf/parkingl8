BASE_DOCKER_COMPOSE = docker-compose -f build/docker-compose.yml -d

up:
	$(BASE_DOCKER_COMPOSE) up --force-recreate
.PHONY: up

kill:
	$(BASE_DOCKER_COMPOSE) kill
.PHONY: kill

composer:
	$(BASE_DOCKER_COMPOSE) exec app /usr/local/bin/composer $(filter-out $@,$(MAKECMDGOALS))
.PHONY: composer

artisan:
	$(BASE_DOCKER_COMPOSE) exec app php artisan $(filter-out $@,$(MAKECMDGOALS))
.PHONY: artisan

sh:
	$(BASE_DOCKER_COMPOSE) exec app bash
.PHONY: sh

cc:
	$(BASE_DOCKER_COMPOSE) exec app php artisan cache:clear
	$(BASE_DOCKER_COMPOSE) exec app php artisan config:clear
	$(BASE_DOCKER_COMPOSE) exec app php artisan route:clear
.PHONY: cc

build:
	$(BASE_DOCKER_COMPOSE) stop \
	&& $(BASE_DOCKER_COMPOSE) rm -f \
	&& $(BASE_DOCKER_COMPOSE) pull \
	&& $(BASE_DOCKER_COMPOSE) build --no-cache
.PHONY: build

test:
	$(BASE_DOCKER_COMPOSE) exec app php vendor/bin/phpunit
.PHONY: test

test-coverage:
	$(BASE_DOCKER_COMPOSE) exec app php vendor/bin/phpunit --coverage-html ./docs/report --testdox-html ./docs/testdox.html --testdox-text ./docs/testdox.txt
.PHONY: test-coverage

%:
	@: