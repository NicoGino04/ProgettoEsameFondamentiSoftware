up:
	docker compose up -d
down:
	docker compose down
bash:
	docker compose exec php_cli /bin/bash
fixtures:
	docker compose exec php_cli /bin/bash -c "php bin/console doctrine:fixtures:load -n"
migrate:
	docker compose exec php_cli /bin/bash -c "php bin/console doctrine:migrations:migrate"
migration:
	docker compose exec php_cli /bin/bash -c "php bin/console make:migration"
