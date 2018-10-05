# Test task

### For deploy use next commands:
Start docker
```
    $ cd docker && docker-compose up -d
```
Enter to docker workspace container
```
    $ docker-compose exec -u laradock worksapce bash
```
Setup DB
```
    $ bin/console doctrine:schema:drop --force
    $ bin/console doctrine:schema:create
    $ bin/console doctrine:schema:update --force
    $ bin/console doctrine:fixtures:load
```
Setup app: setup `app/config/parameters.yml`

Open `http://localhost`
