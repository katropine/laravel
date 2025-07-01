
docker compose required

## GET all data

http://localhost:8000/api/contacts/search

## POST Upsert

http://localhost:8000/api/contacts/upsert

{"name":"Kriss Kross","phone":"+61432345678","email":"kriss@example.net"}

## DELETE Contact

http://localhost:8000/api/contacts/{id}

## GET Contact - show single

http://localhost:8000/api/contacts/{id}

can be found in src/app/Http/Controllers/ContactController.php

routs definition: src/routs/api.php


## CLI

contact:search {--name=} {--phone=} {--email_domain=}

can be found in src/app/Console/Commands/ContactSearch.php


## Text Cases

docker-compose exec app php artisan test --filter=ContactApiTest

docker-compose exec app php artisan test --filter=ContactCliTest


## Solution

A Service (ContactService) class created that implements data layer interactions acording to required functionality. ContactService can be reused for API (HTTP) and CLI (Command) controllers. 