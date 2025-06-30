


## GET all data

http://localhost:8000/api/contacts/search

## POST Upsert

http://localhost:8000/api/contacts/upsert

{"name":"Kriss Kross","phone":"+61432345678","email":"kriss@example.net"}

## DELETE Contact

http://localhost:8000/api/contacts/{id}

## GET Contact - show single

http://localhost:8000/api/contacts/{id}


## CLI

contact:search {--name=} {--phone=} {--email_domain=}


## Text Cases
docker-compose exec app php artisan test --filter=ContactApiTest
docker-compose exec app php artisan test --filter=ContactCliTest