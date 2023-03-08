BASE_IMAGE_NAME=customer-service-app
INTERACTIVE:=$(shell [ -t 0 ] && echo 1)

#
# commands a developer will use
#

build: fe-build be-build

start: build
	docker-compose up --no-recreate -d app

stop:
	docker-compose down

format: fe-develop
	docker-compose run fe-develop make _format

develop: build
	docker-compose up --remove-orphans --no-recreate -d fe-develop be-develop

#
# Frontend-specific commands
#

fe-build:
	docker build -t ${BASE_IMAGE_NAME}-fe -f Dockerfile.frontend .

fe-develop: fe-build
	docker-compose up --remove-orphans --no-recreate -d fe-develop

fe-save-deps:
	docker-compose run fe-develop make _fe-save-deps

_fe-install-deps:
	echo "installing deps"
	echo ${INTERACTIVE}
	echo "installing deps"
	npm i

_fe-save-deps: _fe-install-deps
	npm update

_fe-format:
	npx prettier --write .

fe-install: npm-install

npm-install:
	docker-compose run fe-develop npm install --save-exact $(filter-out $@, $(MAKECMDGOALS))

# php-install:
#   unfortunately must add packages manually to composer.json

#
# Backend-specific commands
#

be-build:
	docker build -t ${BASE_IMAGE_NAME}-be -f Dockerfile.backend .

be-develop: be-build
	docker-compose up --remove-orphans --no-recreate -d be-develop

be-update-deps:
	docker-compose run be-develop make _be-update-deps

_be-install-deps:
	pip3 install -r requirements.txt

_be-save-deps: _be-install-deps
	pip3 freeze > requirements.txt

_be-format:
	isort --float-to-top -r backend
	autoflake --remove-all-unused-imports --recursive --remove-unused-variables --in-place backend
	black backend

be-install: pip-install

pip-install:
	docker-compose run be-develop pip3 install $(filter-out $@, $(MAKECMDGOALS))

#
# commands to be used by docker-container or if a user knows what they are doing
#

#
# to enable cli args
#

%:
	@true
