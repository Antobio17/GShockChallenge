# GStockChallenge
GStock technical challenge.

## Installation and use

For the execution of this challenge the Dockerfile and docker-compose.yaml files have been configured to contain the 
application. The first thing to do is to launch the created services:

```shell
docker-compose up --build -d 
# With the --build option we will build the image. 
# With the -d option we will leave the process in the background without blocking the terminal.
```

Once the services are running we will proceed to install the project dependencies and perform the database migration:

```shell
# We will install the dependencies with:
docker-compose exec php-service-gshock composer install --ignore-platform-reqs

# We will create and execute the database migration with:
docker-compose exec php-service-gshock bin/console doctrine:migrations:diff
docker-compose exec php-service-gshock bin/console doctrine:migrations:execute --up 'DoctrineMigrations\VersionXXXXXXXXXXXXXX'
```

With the environment running we can proceed to make requests to the API in http://localhost:8000/.

### Dotenv files

#### .env

```dotenv
APP_ENV=dev
APP_SECRET='$ecretf0rt3st'
DATABASE_URL="mysql://root:secret@mysql-service:3306/db"
```

#### .env.test

```dotenv
# define your env variables for the test env here
KERNEL_CLASS='App\Kernel'
APP_SECRET='$ecretf0rt3st'
SYMFONY_DEPRECATIONS_HELPER=999999
PANTHER_APP_ENV=panther
PANTHER_ERROR_SCREENSHOT_DIR=./var/error-screenshots
DATABASE_URL="mysql://root:secret@mysql-service:3306/db"
```

## Testing

A battery of tests have been created to test the use cases of the application as well as some unit functions.
To launch these tests we only need to execute the following commands (with the docker services up):

```shell
docker exec -it php-container-gshock bash
# Once inside the container we execute
bin/phpunit
```

## Endpoint calls with Postman

### Order creation
JSON example (To perform the tests it is necessary to use a current timestamp for the **orderAtTimestamp**):
```json
{
    "orderAtTimestamp": 1668937652,
    "reference": "UdIdT5sdsss3l242",
    "remarks": "Test observations for the new order to be created.",
    "orderLines": [
        {
        "product": "Producto 1",
        "quantity": 10,
        "amount": 28.4,
        "iva": 21
        },
        {
            "product": "Producto 2",
            "quantity": 2,
            "amount": 15.6,
            "iva": 21
        }
    ]
}
```
![Order creation](doc/images/img.png)

### Order retrieval
![Order retrieval](doc/images/img_1.png)

