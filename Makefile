install:
	composer install
	npm install
	cp .env.example .env
	php artisan key:generate
	php artisan storage:link
	php artisan migrate --seed
	npm run build

serve:
	php artisan serve

test:
	php artisan test

quality:
	vendor/bin/pint --test
	vendor/bin/phpstan analyse --memory-limit=1G
	php artisan test

fix:
	vendor/bin/pint

docker-up:
	docker compose up --build
