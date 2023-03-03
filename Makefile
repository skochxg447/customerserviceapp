IMAGE_NAME=customer-service-app

#
# commands a developer will use
#

build:
	docker build -t ${IMAGE_NAME} -f Dockerfile .

start: build
	docker-compose up --no-recreate -d app

stop:
	docker-compose down

develop: build
	docker-compose up --no-recreate -d develop

update-deps:
	docker-compose run develop make _update-deps

# php-install:
#   unfortunately must add packages manually to composer.json

npm-install:
	docker-compose run develop npm install --save-exact $(filter-out $@, $(MAKECMDGOALS))

format: develop
	docker-compose run develop npx prettier --write .

#
# commands to be used by docker-container or if a user knows what they are doing
#

# TODO: un-comment-out when php packages are added
_install-deps:
	npm i
# 	composer install

# TODO: un-comment-out when php packages are added
_update-deps:
	npm update
# 	composer update

#
# to enable cli args
#

%:
	@true