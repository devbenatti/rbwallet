# rbwallet

rbwallet is an attempt to simulate a virtual wallet where it is possible to make transfers between its users.

## Features

- Create a user, which can be common or merchant
- Transfer between users, with merchants only receiving transfers.

## Tech

- [Slim](https://www.slimframework.com/) - micro framework for PHP
- [Guzzle](https://docs.guzzlephp.org/en/stable/) - PHP HTTP client
- [DBAL](https://www.doctrine-project.org/projects/doctrine-dbal/en/latest/reference/query-builder.html) - Doctrine database abstraction & access layer.
- [Mysql](https://www.mysql.com/) - The world's most popular open source database

## Installation

rbwallet requires [Docker](https://www.docker.com/get-started) and [Docker Compose](https://docs.docker.com/compose/install/).

Install the dependencies and devDependencies and start the server.

```sh
git clone https://github.com/devbenatti/rbwallet.git
cd rbwallet
make configure
docker-compose up -d
```
## Create user

To create a user, you must send an HTTP POST request for route `/create`. Application will return HTTP status `201` if the creation was a success. Below is an example of payload.
```json
{
    "email": "xablau@gmail.com",
    "name": "Xablau Testing",
    "password": "123",
    "document": "11181756039"
}
```

#### Note: 
If you send a document with 14 digits the application will automatically understand that you are a merchant. It's a bad idea, I know, but I will improve it in other versions.

## Transaction

To send money to another user, you must send an HTTP POST request for route `/transaction`. Application will return HTTP status `201` and a transaction `code` if the creation was a success. Below is an example of payload.
```json
{
    "amount": 123.22,
    "currency": "BRL",
    "payer": 2,
    "payee": 1
}
```
### Status
| Code | Description |
| ------ | ------ |
| 1 | PROCESSING |
| 2 | SUCCESS |
|3 | FAILED |

If payer dont have funds to transfer, application will create a transaction with status `FAILED` and set reason to  `insufficient_funds`. If the transaction is not authorized, application will create transaction with status `FAILED` and reason `unauthorized`. 

## Test

To run tests
```sh
make test
```

## Roadmap
- Deposit money
- Withdraw money
