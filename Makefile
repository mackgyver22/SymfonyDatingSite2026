.PHONY: build up down restart logs shell jwt-keys migrate

build:
	docker compose build --no-cache

up:
	docker compose up -d

down:
	docker compose down

restart:
	docker compose restart

logs:
	docker compose logs -f php

shell:
	docker compose exec php bash

jwt-keys:
	docker compose exec php sh -c ' \
		mkdir -p config/jwt && \
		openssl genpkey -algorithm RSA -out config/jwt/private.pem -pkeyopt rsa_keygen_bits:4096 -aes-256-cbc -pass env:JWT_PASSPHRASE && \
		openssl pkey -in config/jwt/private.pem -out config/jwt/public.pem -pubout -passin env:JWT_PASSPHRASE && \
		chown www-data:www-data config/jwt/private.pem && \
		chmod 640 config/jwt/private.pem \
	'

migrate:
	docker compose exec php php bin/console doctrine:migrations:migrate --no-interaction

db-create:
	docker compose exec php php bin/console doctrine:database:create --if-not-exists

cache-clear:
	docker compose exec php php bin/console cache:clear

setup: build up
	@echo "Waiting for containers to be ready..."
	@sleep 5
	$(MAKE) jwt-keys
	$(MAKE) db-create
	$(MAKE) migrate
	@echo "Setup complete. API available at http://localhost:8080"
