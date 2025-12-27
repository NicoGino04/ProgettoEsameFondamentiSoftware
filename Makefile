up:
	docker compose up -d
bash:
	docker compose exec php_cli /bin/bash
fixtures:
	docker compose exec php_cli /bin/bash -c "php bin/console doctrine:fixtures:load -n"
