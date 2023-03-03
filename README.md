# csa - customer service app

An app that allows clients and/or professionals to input and retrieve data on the client's preferences for customer service. Ex. Prefers jokes, relaxed service, a longer wait before ordering.

# Getting Started

Build the docker image

```shell
make build
```

Run the production server locally

```shell
make run
```

Then navigate to `localhost:8888` see your app.

You can stop any running background servers anytime with:

```shell
make stop
```

# Development

Run the development server locally

```shell
make develop
```

Then navigate to `localhost:8888` see your app.

## Managing dependencies

In order to install a new dependency, run:

```shell
make npm-install <package-name>
```

or

NOTE: this currently isn't functional because we aren't using any PHP dependencies yet

```shell
make php-install
```

## Run Formatting

```shell
make format
```
