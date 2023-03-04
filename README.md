# Basic Implementation Laravel 9 DDD

## Technologies
- [JWT](https://jwt.io/) 
- [Redis](https://redis.io/)
- [Msql](https://www.mysql.com/)
- [Nginx](https://www.nginx.com/)
- [Sonarqube](https://docs.sonarqube.org/latest/)

## Features
- Authentication with [Tymon's JWT Auth](https://github.com/tymondesigns/jwt-auth)
- Basic functions CR of the Vacancies domain
- Feature tests for Vacancies
- Sonarqube Implementation

## First steps
1. ```git clone proyect```
2. ```cd proyect folder```
3. ```docker-compose up --build -d```
4. ```docker ps```
5. ```docker exec -it #{CONTAINER_ID php} bash```
6. ```composer install```
7. ```cp .env.example .env```
8. ```php artisan migrate --seed```
9. ```php artisan test```
10. ```php artisan test --coverage```

## Structure
- It is located in the ./src folder
- In the config/app.php route, the VacanciesServiceProvider Service Provider is added, which adds some Macros that extend the operation of json responses
- Laravel Models: User, Vacancies
```
...
├── Vacancies
│   ├── Application 
│   ├── Domain
│   └── Infrastructure
└── Shared
    └── Domain
...
```

## Execute Sonarqube
1. ```docker run -d --name sonarqube -e SONAR_ES_BOOTSTRAP_CHECKS_DISABLE=true -p 9000:9000 sonarqube:latest```
2. [Sonarqube https://localhost:9000](https://localhost:9000) ```user: admin password: admin```
3. ```docker run --network=host -e SONAR_HOST_URL='http://localhost:9000' -e SONAR_SCANNER_OPTS="-Dsonar.projectKey=monoma" -e SONAR_LOGIN="sqp_8bfce47d3ed173e6baed2c59bfaabc27c613b66d" --user="$(id -u):$(id -g)" -v "$PWD:/usr/src" sonarsource/sonar-scanner-cli```
