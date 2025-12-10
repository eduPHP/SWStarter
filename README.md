# SWStarter

A SWAPI implementation

## get it running

```
git clone https://github.com/eduPHP/SWStarter.git eduardo-dalla-vecchia-assignment
cd eduardo-dalla-vecchia-assignment
docker compose up -d --build
```

## access the app

- Search: http://localhost:8011
- Stats: http://localhost:8011/stats

## run the tests

```
docker compose exec -it app php artisan test
```
