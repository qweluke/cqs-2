Yet another CQS implementation ;-)
=======

A Symfony 3.2 project created on May 16, 2017, 8:53 am.
This project requires PHP 7.x.

### Project statistics
Please visit [https://qweluke.github.io/cqs-2/build/index.html](https://qweluke.github.io/cqs-2/build/index.html) to see the PHPMetrics statistics and PHPUnit code coverage  


### Installation

##### Step 1: Download this project

```bash
git clone
```

##### Step 2: Download all dependencies

```bash
composer update
```

##### Step 3: create database and tables 

```bash
php bin/console doctrine:database:create
php bin/console doctrine:schema:update --force
```

### Usage

##### Step 1: run this project locally 

```bash
php bin/console server:start 0.0.0.0:8085
```

##### Step 2: go to you browser and run:
 
 http://localhost:8085