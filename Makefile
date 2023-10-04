build:
	make pre-setup
	docker-compose build --parallel
build-no-cache:
	make pre-setup
	docker-compose build --parallel --no-cache
build-php:
	docker-compose build ivory-php
build-worker:
	docker-compose build ivory-worker
build-db:
	docker-compose build ivory-postgres
build-daemon:
	docker-compose up -d --build
dev:
	docker-compose up
dev-daemon:
	docker-compose up -d
down:
	docker-compose down
api-setup:
	make api-install
api-install:
	docker exec -it ivory-api composer install --no-cache --ignore-platform-reqs && \
	docker exec -it ivory-api yarn
api-key:
	docker exec -it ivory-api php artisan key:generate
api-debug:
	docker exec -it ivory-api php artisan serve --host 0.0.0.0 --port 8000
api-migrate:
	docker exec -it ivory-api php artisan migrate
api-migrate-refresh:
	docker exec -it ivory-api php artisan migrate:fresh --seed
api-serve:
	docker exec -it ivory-api yarn serve
api-ssh:
	docker exec -it ivory-api /bin/bash
api-restart:
	docker-compose restart ivory-api --no-deps
worker-listen:
	php artisan queue:listen
migrate:
	docker exec -it ivory-api php artisan migrate
pre-setup:
	dev-env/pre-setup.sh
setup:
	make build
	make dev-daemon
	make post-setup
post-setup:
	dev-env/setup.sh
build-deploy-php:
	docker build -f ./php.deploy.dockerfile -t ivory:1.0.0 .
run-deploy-php:
	docker run --name php-deploy -p 8000:8000 ivory:1.0.0
run-worker:
	docker run --name ivory-worker -p 8000:8000 ivory:1.0.0
test-php:
	docker exec docker-swoole-php /usr/src/api/vendor/bin/phpunit \
	--configuration /usr/src/api/phpunit.xml \
	--colors=auto