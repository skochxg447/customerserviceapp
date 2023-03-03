IMAGE_NAME=customer-service-app

build:
	docker build -t ${IMAGE_NAME} -f Dockerfile .

app:
	docker-compose up app

develop:
	docker-compose up develop