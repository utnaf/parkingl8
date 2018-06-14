# ParkingL8

Trello board: [https://trello.com/b/OhoByDF1/parkingl8](https://trello.com/b/OhoByDF1/parkingl8)

## How to use


Run on a Docker environment, so be sure to have Docker installed and run the following commands, hopefully it will work, I didn't test this part yet.

```bash
make up
cp .env.example .env
make artisan key:generate
make artisan migrate
make db:seed
```

The app runs on port 8080, so please visit: `[yourdockerip]:8080` or add an entry in your hosts file:
```
[yourdockerip] parkingl8.local
```
then visit `parkingl8.local:8080`

The above commands must be ran only the first time. Daily work consists of:

```bash
make up
make kill # to kill the containers obviously
```

Be sure to run the migration (`make artisan migrate`) after you pull, just in case.
Hopefully I'll create some composer hooks. 

Other useful commands:
```
make test # runs the tests
make cc # clear application caches
```