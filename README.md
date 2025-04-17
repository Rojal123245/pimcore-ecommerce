# Swiss Engineering Pimcore

## Getting started
```bash
git clone git@gitlab.com:a4-agentur/swissengineering.git
cd ./swissengineering
docker-compose up -d
docker-compose exec php composer install
docker-compose exec php vendor/bin/pimcore-install --mysql-host-socket=db
``` 
- Open http://localhost in your browser
- Done! 😎