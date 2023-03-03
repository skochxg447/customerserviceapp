IMAGE_NAME=customer-service-app

build:
	docker build -t ${IMAGE_NAME} -f Dockerfile .

run:
	docker-compose up
