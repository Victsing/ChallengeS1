# Project setup

## ApiPlatform

Dans le dossier ApiPlaform :

### Setup
```
docker-compose build --pull --no-cache
docker-compose up -d
docker-compose exec php composer i
```

### Generate keys
```
docker-compose exec php bin/console lexik:jwt:generate-keypair
```

### Make entity
```
docker-compose exec php bin/console m:e
```

## Vue App

Dans le dossier vue-app :

### Setup
```
docker-compose up -d
docker-compose exec node npm i
```

### Run
```
docker-compose exec node npm run dev
```
blabla