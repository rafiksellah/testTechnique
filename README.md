# testTechnique

# Project Name - Symfony 7 & PHP8 - Docker

This project is a simple Symfony 7 application running on PHP8, using Docker for environment setup. It allows users to create, read, update, and delete articles. This README will guide you through the setup, installation, and running of the project.

## Features

- **Create Articles**: Users can create new articles with a title, content, and publication date.
- **Read Articles**: Visitors can view the list of articles and read the details of each one.
- **Update Articles**: Users can edit existing articles.
- **Delete Articles**: Users can delete articles.

## Requirements

Before you begin, make sure you have the following installed on your machine:

- **Docker**: [Install Docker](https://www.docker.com/get-started)
- **Docker Compose**: [Install Docker Compose](https://docs.docker.com/compose/install/)
- **Git**: [Install Git](https://git-scm.com/book/en/v2/Getting-Started-Installing-Git)

## Installation

Follow these steps to get your development environment up and running.

### Step 1: Clone the Repository

Start by cloning the repository to your local machine:

git clone https://github.com/your-username/testTechnique.git

cd testTechnique

## Build and start Docker containers

docker-compose up --build

##Run Symfony Server
docker-compose exec app symfony server:start

#install Dependencies
docker-compose exec app composer install

#create database
docker-compose exec app php bin/console doctrine:database:create

#Run migrations
docker-compose exec app php bin/console doctrine:migrations:migrate

#Load Fixtures (Optional):

docker-compose exec app php bin/console doctrine:fixtures:load

#Run Tests (Optional)

docker-compose exec app php bin/phpunit
   
