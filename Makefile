init:
	composer install
	\cp -r .env.example .env
	./vendor/bin/sail up -d
	./vendor/bin/sail npm install
	./vendor/bin/sail up -d
	./vendor/bin/sail npm run prod
	./vendor/bin/sail up -d
	./vendor/bin/sail artisan migrate --seed
	./vendor/bin/sail down
up:
	./vendor/bin/sail up -d
down:
	docker-compose down
