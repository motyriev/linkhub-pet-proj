LinkHub is a pet project that allows users to create their own personal link pages. Users can add and edit links, track statistics like page visits and link clicks. The project follows Clean Architecture principles and is built using Symfony, PostgreSQL, and ClickHouse for statistics tracking. It also employs Domain-Driven Design (DDD), Test-Driven Development (TDD), and Command Query Responsibility Segregation (CQRS).

## Features

- User registration and authentication (JWT)
- Create and edit personal link page
- Track page visits and link clicks using clickhouse
- Public user page accessible via username-based URLs
- REST API for managing users, links, and statistics
- Follows Clean Architecture, DDD, CQRS, and TDD principles

## Technologies

- **Backend**: symfony 7.1, php 8.3
- **App database**: postgreSQL
- **Statistics database**: clickhouse
- **Testing**: phpunit
- **Messaging**: symfony Messenger (works with Doctrine for background tasks)
- **Authentication**: jwt
- **Documentation**: swagger ui

## Installation

To run the project, follow these steps:

```bash
# clone the repository
git clone https://github.com/motyriev/linkhub-pet-proj.git linkhub

cd linkhub/docker && docker-compose up --build -d

# run composer
docker exec -it app composer install

# run database migrations
docker exec -it app php bin/console doctrine:migrations:migrate

# run clickhouse  migrations for statistics
docker exec -it app php bin/console app:clickhouse:migrate

# generate JWT keypair for authentication
docker exec -it app php bin/console lexik:jwt:generate-keypair

# run tests
docker exec -it app php bin/phpunit 

# start queue consumers
docker exec -it app php bin/console messenger:consume --all
```

## API Documentation
The project includes a Swagger UI for exploring the API. You can access it locally at: 
http://localhost:999/api/doc

- POST `/api/statistics/links/{linkId}/clicks`: Record a click on a link.
- POST `/api/statistics/pages/{pageId}/visits`: Record a visit to a page.
- GET `/api/users/me`: Retrieve the authenticated user's information.
- POST `/api/users/register`: Register a new user.
- POST `/api/auth/token`: Authenticate and get a JWT token.
- GET `/api/user/page`: Retrieve the authenticated user's page.
- PUT `/api/user/page`: Update the authenticated user's page.
- GET `/api/page/{username}`: Get a public view of a user's page.